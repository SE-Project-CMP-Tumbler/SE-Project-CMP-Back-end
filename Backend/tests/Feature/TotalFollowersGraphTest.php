<?php

namespace Tests\Feature;

use App\Http\Misc\Helpers\Config;
use App\Models\User;
use App\Models\Blog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TotalFollowersGraphTest extends TestCase
{
    use RefreshDatabase;

    protected $notesBaseGraphRoute = '/api/graph/total_followers/';

    protected $accessToken;

    protected $blogId;
    protected $tmpBlogId;

    // last: day, 3 days, week, month
    protected $allowedPeriods = [1, 3, 7, 30];

    // hourly, daily
    protected $allowedRates = [0, 1];

    protected $responseStructure = [
        "meta" => [
            "status",
            "msg",
        ],
        "response" => [
            "notes_count",
            "new_followers_count",
            "total_followers_count",
            "data" => [
                '*' => [
                    'total_followers',
                    'timestamp',
                ],
            ],
        ],
    ];

    public function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create();
        $this->accessToken = $user->createToken('Auth Token')->accessToken;
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        $this->blogId = $blog->id;

        $tmpUser = User::factory()->create();
        $tmpBlog = Blog::factory()->create(['user_id' => $tmpUser->id]);
        $this->tmpBlogId = $tmpBlog->id;
    }

    public function testUnauthorizedRequest()
    {
        $response = $this->getJson(
            $this->notesBaseGraphRoute
            . $this->blogId . '/'
            . $this->allowedPeriods[2] . '/'
            . $this->allowedRates[1],
            Config::JSON
        );
        $response->assertUnauthorized();
    }

    public function testNotSendingBlogId()
    {
        $response = $this->getJson(
            $this->notesBaseGraphRoute
            . $this->allowedPeriods[2] . '/'
            . $this->allowedRates[1],
            array_merge(Config::JSON, [
            'Authorization' => 'Bearer ' . $this->accessToken,
            ])
        );
        $response->assertStatus(404);
        $response->assertJson([
            'meta' => [
                'status' => '404',
                'msg' => 'Not Found'
            ]
        ]);
    }

    public function testNotSendingPeriod()
    {
        $response = $this->getJson(
            $this->notesBaseGraphRoute
            . $this->blogId . '/'
            . $this->allowedRates[1],
            array_merge(Config::JSON, [
            'Authorization' => 'Bearer ' . $this->accessToken,
            ])
        );
        $response->assertStatus(404);
        $response->assertJson([
            'meta' => [
                'status' => '404',
                'msg' => 'Not Found'
            ]
        ]);
    }

    public function testNotSendingRate()
    {
        $response = $this->getJson(
            $this->notesBaseGraphRoute
            . $this->blogId . '/'
            . $this->allowedPeriods[2],
            array_merge(Config::JSON, [
                'Authorization' => 'Bearer ' . $this->accessToken,
            ])
        );
        $response->assertStatus(404);
        $response->assertJson([
            'meta' => [
                'status' => '404',
                'msg' => 'Not Found'
            ]
        ]);
    }

    public function testSendingNonIntBlogId()
    {
        $response = $this->getJson(
            $this->notesBaseGraphRoute
            . 'abc/'
            . $this->allowedPeriods[2] . '/'
            . $this->allowedRates[1],
            array_merge(Config::JSON, [
                'Authorization' => 'Bearer ' . $this->accessToken,
            ])
        );
        $response->assertStatus(422);
        $response->assertJson([
            'meta' => [
                'status' => '422',
                'msg' => 'The blog id must be an integer.'
            ]
        ]);
    }

    public function testSendingNotExistBlogId()
    {
        $lastBlogID = Blog::orderBy('id', 'desc')->first();
        if ($lastBlogID) {
            $lastBlogID = $lastBlogID->id + 10;
        } else {
            $lastBlogID = 10;
        }
        $response = $this->getJson(
            $this->notesBaseGraphRoute
            . $lastBlogID . '/'
            . $this->allowedPeriods[2] . '/'
            . $this->allowedRates[1],
            array_merge(Config::JSON, [
                'Authorization' => 'Bearer ' . $this->accessToken,
            ])
        );
        $response->assertStatus(422);
        $response->assertJson([
            'meta' => [
                'status' => '422',
                'msg' => 'The selected blog id is invalid.'
            ]
        ]);
    }

    public function testSendingBlogIdNotBelongToCurrenUser()
    {
        $response = $this->getJson(
            $this->notesBaseGraphRoute
            . $this->tmpBlogId . '/'
            . $this->allowedPeriods[2] . '/'
            . $this->allowedRates[1],
            array_merge(Config::JSON, [
                'Authorization' => 'Bearer ' . $this->accessToken,
            ])
        );
        $response->assertStatus(422);
        $response->assertJson([
            'meta' => [
                'status' => '422',
                'msg' => 'The selected blog id is invalid.'
            ]
        ]);
    }

    public function testSendingNonIntPeriod()
    {
        $response = $this->getJson(
            $this->notesBaseGraphRoute
            . $this->blogId . '/'
            . 'abc/'
            . $this->allowedRates[1],
            array_merge(Config::JSON, [
                'Authorization' => 'Bearer ' . $this->accessToken,
            ])
        );
        $response->assertStatus(422);
        $response->assertJson([
            'meta' => [
                'status' => '422',
                'msg' => 'The period must be an integer.'
            ]
        ]);
    }

    public function testSendingNotAllowedPeriod()
    {
        $response = $this->getJson(
            $this->notesBaseGraphRoute
            . $this->blogId . '/'
            . 60 . '/'
            . $this->allowedRates[1],
            array_merge(Config::JSON, [
                'Authorization' => 'Bearer ' . $this->accessToken,
            ])
        );
        $response->assertStatus(422);
        $response->assertJson([
            'meta' => [
                'status' => '422',
                'msg' => 'The selected period is invalid.'
            ]
        ]);
    }

    public function testSendingNonIntRate()
    {
        $response = $this->getJson(
            $this->notesBaseGraphRoute
            . $this->blogId . '/'
            . $this->allowedPeriods[1] . '/'
            . 'abc',
            array_merge(Config::JSON, [
                'Authorization' => 'Bearer ' . $this->accessToken,
            ])
        );
        $response->assertStatus(422);
        $response->assertJson([
            'meta' => [
                'status' => '422',
                'msg' => 'The rate must be an integer.'
            ]
        ]);
    }

    public function testSendingNotAllowedRate()
    {
        $response = $this->getJson(
            $this->notesBaseGraphRoute
            . $this->blogId . '/'
            . $this->allowedPeriods[1] . '/'
            . 60,
            array_merge(Config::JSON, [
                'Authorization' => 'Bearer ' . $this->accessToken,
            ])
        );
        $response->assertStatus(422);
        $response->assertJson([
            'meta' => [
                'status' => '422',
                'msg' => 'The selected rate is invalid.'
            ]
        ]);
    }

    public function testSendingValidRequest()
    {
        $response = $this->getJson(
            $this->notesBaseGraphRoute
            . $this->blogId . '/'
            . $this->allowedPeriods[1] . '/'
            . $this->allowedRates[1],
            array_merge(Config::JSON, [
                'Authorization' => 'Bearer ' . $this->accessToken,
            ])
        );
        $response->assertStatus(200);
        $response->assertJsonStructure($this->responseStructure);
    }
}

