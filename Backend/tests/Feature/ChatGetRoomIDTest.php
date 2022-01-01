<?php

namespace Tests\Feature;

use App\Http\Misc\Helpers\Config;
use App\Models\User;
use App\Models\Blog;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChatGetRoomIDTest extends TestCase
{
    use RefreshDatabase;

    protected $chatRoomIDEndPoint = '/api/chat/chat_id';

    protected User $userA;
    protected Blog $blogA;
    protected string $actA;

    protected User $userB;
    protected Blog $blogB;
    protected string $actB;

    protected $chatRoomIDResponseStructure = [
        "meta" => [
            "status",
            "msg",
        ],
        "response" => [
            "chat_room_id",
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
        $response = $this->postJson($this->chatRoomIDEndPoint, [
            'from_blog_id' => $this->blogA->id,
            'to_blog_id' => $this->blogB->id,
        ], Config::JSON);
        $response->assertUnauthorized();
    }

    public function testSendingEmptyRequest()
    {
        $response = $this->postJson($this->chatRoomIDEndPoint, [
            'from_blog_id' => null,
            'to_blog_id' => null,
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

    public function testSendingNonIntFromBlogID()
    {
        $response = $this->postJson($this->chatRoomIDEndPoint, [
            'from_blog_id' => 'abc',
            'to_blog_id' => null,
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

    public function testNotSendingToBlogID()
    {
        $response = $this->postJson($this->chatRoomIDEndPoint, [
            'from_blog_id' => $this->blogA->id,
            'to_blog_id' => null,
        ], array_merge(Config::JSON, [
            'Authorization' => 'Bearer ' . $this->actA,
        ]));
        $response->assertStatus(422);
        $response->assertJson([
            'meta' => [
                'status' => '422',
                'msg' => 'The to blog id field is required.'
            ]
        ]);
    }

    public function testSendingNonIntToBlogID()
    {
        $response = $this->postJson($this->chatRoomIDEndPoint, [
            'from_blog_id' => $this->blogA->id,
            'to_blog_id' => 'abc',
        ], array_merge(Config::JSON, [
            'Authorization' => 'Bearer ' . $this->actA,
        ]));
        $response->assertStatus(422);
        $response->assertJson([
            'meta' => [
                'status' => '422',
                'msg' => 'The to blog id must be an integer.'
            ]
        ]);
    }

    public function testSendingFromBlogIDDoesnotBelongToCurrentUser()
    {
        $response = $this->postJson($this->chatRoomIDEndPoint, [
            'from_blog_id' => $this->blogB->id,
            'to_blog_id' => $this->blogA->id,
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

    public function testSendingNonExistToBlogID()
    {
        $lastBlogID = Blog::orderBy('id', 'desc')->first();
        if ($lastBlogID) {
            $lastBlogID = $lastBlogID->id + 10;
        } else {
            $lastBlogID = 10;
        }
        $response = $this->postJson($this->chatRoomIDEndPoint, [
            'from_blog_id' => $this->blogA->id,
            'to_blog_id' => $lastBlogID,
        ], array_merge(Config::JSON, [
            'Authorization' => 'Bearer ' . $this->actA,
        ]));
        $response->assertStatus(422);
        $response->assertJson([
            'meta' => [
                'status' => '422',
                'msg' => 'The selected to blog id is invalid.'
            ]
        ]);
    }

    public function testChattingBetweenSameBlogs()
    {
        $response = $this->postJson($this->chatRoomIDEndPoint, [
            'from_blog_id' => $this->blogA->id,
            'to_blog_id' => $this->blogA->id,
        ], array_merge(Config::JSON, [
            'Authorization' => 'Bearer ' . $this->actA,
        ]));
        $response->assertStatus(422);
        $response->assertJson([
            'meta' => [
                'status' => '422',
                'msg' => 'The to blog id and from blog id must be different.'
            ]
        ]);
    }

    public function testSendingValidRequest()
    {
        $response = $this->postJson($this->chatRoomIDEndPoint, [
            'from_blog_id' => $this->blogA->id,
            'to_blog_id' => $this->blogB->id,
        ], array_merge(Config::JSON, [
            'Authorization' => 'Bearer ' . $this->actA,
        ]));
        $response->assertStatus(200);
        $response->assertJsonStructure($this->chatRoomIDResponseStructure);
    }
}
