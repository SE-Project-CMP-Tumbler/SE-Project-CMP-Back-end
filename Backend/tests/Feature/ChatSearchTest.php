<?php

namespace Tests\Feature;

use App\Http\Misc\Helpers\Config;
use App\Models\User;
use App\Models\Blog;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChatSearchTest extends TestCase
{
    use RefreshDatabase;

    protected $chatSearchEndPoint = '/api/chat/chat_search';

    protected User $userA;
    protected Blog $blogA;
    protected string $actA;

    protected User $userB;
    protected Blog $blogB;

    protected $chatSearchResponseStructure = [
        "meta" => [
            "status",
            "msg",
        ],
        "response" => [
            "blogs" => [
               '*' => [
                    "friend_id",
                    "friend_username",
                    "friend_avatar",
                    "friend_avatar_shape",
                    "friend_title",
                ]
            ],
        ],
    ];

    /**
     * Require the access token for the testing user before running each testcase
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        // user A
        $this->userA = User::factory()->create();
        $this->actA = $this->userA->createToken('Auth Token')->accessToken;
        $this->blogA = Blog::factory()->create(['user_id' => $this->userA->id]);

        // user B
        $this->userB = User::factory()->create();
        $this->blogB = Blog::factory()->create(['user_id' => $this->userB->id]);
    }

    public function testUnauthorizedRequest()
    {
        $response = $this->postJson($this->chatSearchEndPoint, [
            'blog_username' => 'abc',
            'from_blog_id' => $this->blogA->id,
        ], Config::JSON);
        $response->assertUnauthorized();
    }

    public function testSendingEmptyRequest()
    {
        $response = $this->postJson($this->chatSearchEndPoint, [
            'blog_username' => null,
            'from_blog_id' => null,
        ], array_merge(Config::JSON, [
            'Authorization' => 'Bearer ' . $this->actA,
        ]));
        $response->assertStatus(422);
        $response->assertJson([
            'meta' => [
                'status' => '422',
                'msg' => 'The blog username field is required.'
            ]
        ]);
    }

    public function testSendingNonStingBlogUsername()
    {
        $response = $this->postJson($this->chatSearchEndPoint, [
            'blog_username' => 123,
            'from_blog_id' => null,
        ], array_merge(Config::JSON, [
            'Authorization' => 'Bearer ' . $this->actA,
        ]));
        $response->assertStatus(422);
        $response->assertJson([
            'meta' => [
                'status' => '422',
                'msg' => 'The blog username must be a string.'
            ]
        ]);
    }

    public function testNotSendingFromBlogID()
    {
        $response = $this->postJson($this->chatSearchEndPoint, [
            'blog_username' => 'ab',
            'from_blog_id' => null,
        ], array_merge(Config::JSON, [
            'Authorization' => 'Bearer ' . $this->actA,
        ]));
        $response->assertStatus(422);
        $response->assertJson([
            'meta' => [
                'status' => '422',
                'msg' => 'The from blog id field is required.'
            ]
        ]);
    }

    public function testSendingNonExistFromBlogID()
    {
        $lastBlogID = Blog::orderBy('id', 'desc')->first();
        if ($lastBlogID) {
            $lastBlogID = $lastBlogID->id + 10;
        } else {
            $lastBlogID = 10;
        }
        $response = $this->postJson($this->chatSearchEndPoint, [
            'blog_username' => 'ab',
            'from_blog_id' => $lastBlogID,
        ], array_merge(Config::JSON, [
            'Authorization' => 'Bearer ' . $this->actA,
        ]));
        $response->assertStatus(422);
        $response->assertJson([
            'meta' => [
                'status' => '422',
                'msg' => 'The selected from blog id is invalid.'
            ]
        ]);
    }

    public function testSendingNonIntegerFromBlogID()
    {
        $response = $this->postJson($this->chatSearchEndPoint, [
            'blog_username' => 'ab',
            'from_blog_id' => 'xyz123',
        ], array_merge(Config::JSON, [
            'Authorization' => 'Bearer ' . $this->actA,
        ]));
        $response->assertStatus(422);
        $response->assertJson([
            'meta' => [
                'status' => '422',
                'msg' => 'The from blog id must be an integer.'
            ]
        ]);
    }

    public function testSendingFromBlogIDDoesNotBelongToCurrentUser()
    {
        $response = $this->postJson($this->chatSearchEndPoint, [
            'blog_username' => 'ab',
            'from_blog_id' => $this->blogB->id,
        ], array_merge(Config::JSON, [
            'Authorization' => 'Bearer ' . $this->actA,
        ]));
        $response->assertStatus(422);
        $response->assertJson([
            'meta' => [
                'status' => '422',
                'msg' => 'The selected from blog id is invalid.'
            ]
        ]);
    }

    public function testSendingValidRequest()
    {
        $response = $this->postJson($this->chatSearchEndPoint, [
            'blog_username' => substr($this->blogB->username, 0, 4),
            'from_blog_id' => $this->blogA->id,
        ], array_merge(Config::JSON, [
            'Authorization' => 'Bearer ' . $this->actA,
        ]));
        $response->assertStatus(200);
        $response->assertJsonStructure($this->chatSearchResponseStructure);
        // assert if blogB data appears in the response as we searched for it
        $found = false;
        $responseBlogs = $response["response"]["blogs"];
        foreach ($responseBlogs as $blog) {
            if ($blog["friend_id"] == $this->blogB->id) {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found);
    }
}
