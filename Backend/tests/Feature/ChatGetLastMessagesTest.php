<?php

namespace Tests\Feature;

use App\Http\Misc\Helpers\Config;
use App\Models\User;
use App\Models\Blog;
use App\Models\ChatMessage;
use App\Models\ChatRoom;
use App\Models\ChatRoomGID;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChatGetLastMessagesTest extends TestCase
{
    use RefreshDatabase;

    protected $endPoint = '/api/chat/all_chats';

    protected User $userA;
    protected Blog $blogA;
    protected Blog $blogB;
    protected string $actA;

    protected User $userB;
    protected Blog $blogC;

    protected $responseStructure = [
        "meta" => [
            "status",
            "msg",
        ],
        "response" => [
            "chat_messages" => [
                "*" => [
                    "text",
                    "photo",
                    "gif",
                    "read",
                    "blog_username",
                    "blog_id",
                    "blog_avatar",
                    "blog_avatar_shape",
                    "blog_title",
                    "friend_username",
                    "friend_id",
                    "friend_avatar",
                    "friend_avatar_shape",
                    "friend_title",
                ],
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

        // user A has 2 blogs
        $this->userA = User::factory()->create();
        $this->actA = $this->userA->createToken('Auth Token')->accessToken;
        $this->blogA = Blog::factory()->create(['user_id' => $this->userA->id]);
        $this->blogB = Blog::factory()->create(['user_id' => $this->userA->id]);

        // user B has 1 blog
        $this->userB = User::factory()->create();
        $this->blogC = Blog::factory()->create(['user_id' => $this->userB->id]);

        // create a chat room id for those blogA and blogB
        $chatRoomOne = ChatRoom::factory()->create([
            "from_blog_id" => $this->blogA->id,
            "to_blog_id" => $this->blogC->id,
        ]);
        $chatRoomTwo = ChatRoom::factory()->create([
            "from_blog_id" => $this->blogC->id,
            "to_blog_id" => $this->blogA->id,
        ]);
        $chatRoomGID = ChatRoomGID::factory()->create([
            "chat_room_one_id" => $chatRoomOne->id,
            "chat_room_two_id" => $chatRoomTwo->id,
        ]);

        for ($i = 0; $i < 10; $i++) {
            $randChatRoom = rand(0, 10) < 5 ? $chatRoomOne : $chatRoomTwo;
            ChatMessage::factory()->create([
                "chat_room_id" => $randChatRoom->id,
            ]);
        }
    }

    public function testUnauthorizedRequest()
    {
        $response = $this->postJson($this->endPoint, [
            'from_blog_id' => $this->blogA->id,
        ], Config::JSON);
        $response->assertUnauthorized();
    }

    // this will assume getting the messages from and to the userA primary blog
    public function testSendingEmptyRequest()
    {
        $response = $this->postJson($this->endPoint, [
            // empty request body ..
        ], array_merge(Config::JSON, [
            'Authorization' => 'Bearer ' . $this->actA,
        ]));
        $response->assertStatus(200);
        $response->assertJsonStructure($this->responseStructure);
    }

    public function testSendingNonIntFromBlogId()
    {
        $response = $this->postJson($this->endPoint, [
            'from_blog_id' => 'abc',
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

    public function testSendingNonExistFromBlogId()
    {
        $lastBlogID = Blog::orderBy('id', 'desc')->first();
        if ($lastBlogID) {
            $lastBlogID = $lastBlogID->id + 10;
        } else {
            $lastBlogID = 10;
        }
        $response = $this->postJson($this->endPoint, [
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

    public function testSendingFromBlogIdNotBelongToUser()
    {
        $response = $this->postJson($this->endPoint, [
            'from_blog_id' => $this->blogC->id,
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
        $response = $this->postJson($this->endPoint, [
            'from_blog_id' => $this->blogA->id,
        ], array_merge(Config::JSON, [
            'Authorization' => 'Bearer ' . $this->actA,
        ]));
        $response->assertStatus(200);
        $response->assertJsonStructure($this->responseStructure);
    }
}
