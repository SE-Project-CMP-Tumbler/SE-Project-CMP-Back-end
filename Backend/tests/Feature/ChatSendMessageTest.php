<?php

namespace Tests\Feature;

use App\Http\Misc\Helpers\Config;
use App\Models\User;
use App\Models\Blog;
use App\Models\ChatRoom;
use App\Models\ChatRoomGID;
use Tests\TestCase;
use Illuminate\Support\Facades\Event;
use App\Events\ChatMessageEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChatSendMessageTest extends TestCase
{
    use RefreshDatabase;

    protected $storageDriver = 'public';

    protected $baseNewMessageEndPoint = '/api/chat/new_message/';
    protected $newMessageEndPoint;

    protected User $userA;
    protected Blog $blogA;
    protected string $actA;

    protected User $userB;
    protected Blog $blogB;

    protected ChatRoom $chatRoomOne;
    protected ChatRoom $chatRoomTwo;
    protected ChatRoomGID $chatRoomGID;

    protected $newMessageResponseStructure = [
        "meta" => [
            "status",
            "msg",
        ],
        "response" => [
            "chat_messages" => [
                '*' => [
                    "text",
                    "photo",
                    "gif",
                    "read",
                    "blog_username",
                    "blog_id",
                    "blog_avatar",
                    "blog_avatar_shape",
                    "blog_title",
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

        // user A
        $this->userA = User::factory()->create();
        $this->actA = $this->userA->createToken('Auth Token')->accessToken;
        $this->blogA = Blog::factory()->create(['user_id' => $this->userA->id]);

        // user B
        $this->userB = User::factory()->create();
        $this->blogB = Blog::factory()->create(['user_id' => $this->userB->id]);

        // create a chat room id for those two users
        $this->chatRoomOne = ChatRoom::factory()->create([
            "from_blog_id" => $this->blogA->id,
            "to_blog_id" => $this->blogB->id,
        ]);
        $this->chatRoomTwo = ChatRoom::factory()->create([
            "from_blog_id" => $this->blogB->id,
            "to_blog_id" => $this->blogA->id,
        ]);
        $this->chatRoomGID = ChatRoomGID::factory()->create([
            "chat_room_one_id" => $this->chatRoomOne->id,
            "chat_room_two_id" => $this->chatRoomTwo->id,
        ]);

        // changing the end point
        $this->newMessageEndPoint = $this->baseNewMessageEndPoint . $this->chatRoomGID->id;
    }

    public function testUnauthorizedRequest()
    {
        Event::fake();
        $response = $this->postJson($this->newMessageEndPoint, [
            'from_blog_id' => $this->blogA->id,
        ], Config::JSON);
        $response->assertUnauthorized();
        Event::assertNotDispatched(ChatMessageEvent::class);
    }

    public function testSendingEmptyChatRoomID()
    {
        Event::fake();
        $response = $this->postJson($this->baseNewMessageEndPoint, [
            'from_blog_id' => $this->blogA->id,
        ], array_merge(Config::JSON, [
            'Authorization' => 'Bearer ' . $this->actA,
        ]));
        $response->assertStatus(404);
        $response->assertJson([
            'meta' => [
                'status' => '404',
                'msg' => 'Not Found'
            ]
        ]);
        Event::assertNotDispatched(ChatMessageEvent::class);
    }

    public function testSendingNonIntChatRoomID()
    {
        Event::fake();
        $response = $this->postJson($this->baseNewMessageEndPoint . 'abc', [
            'from_blog_id' => $this->blogA->id,
        ], array_merge(Config::JSON, [
            'Authorization' => 'Bearer ' . $this->actA,
        ]));
        $response->assertStatus(422);
        $response->assertJson([
            'meta' => [
                'status' => '422',
                'msg' => 'The chat room id must be an integer.'
            ]
        ]);
        Event::assertNotDispatched(ChatMessageEvent::class);
    }

    public function testSendingNotExistChatRoomID()
    {
        Event::fake();
        $lastChatRoomGID = ChatRoomGID::orderBy('id', 'desc')->first();
        if ($lastChatRoomGID) {
            $lastChatRoomGID = $lastChatRoomGID->id + 10;
        } else {
            $lastChatRoomGID = 10;
        }
        $response = $this->postJson($this->baseNewMessageEndPoint . $lastChatRoomGID, [
            'from_blog_id' => $this->blogA->id,
        ], array_merge(Config::JSON, [
            'Authorization' => 'Bearer ' . $this->actA,
        ]));
        $response->assertStatus(422);
        $response->assertJson([
            'meta' => [
                'status' => '422',
                'msg' => 'The selected chat room id is invalid.'
            ]
        ]);
        Event::assertNotDispatched(ChatMessageEvent::class);
    }

    public function testSendingOnChatRoomIDForOtherBlogs()
    {
        Event::fake();
        $tmp = ChatRoomGID::factory()->create();
        $response = $this->postJson($this->baseNewMessageEndPoint . $tmp->id, [
            'from_blog_id' => $this->blogA->id,
        ], array_merge(Config::JSON, [
            'Authorization' => 'Bearer ' . $this->actA,
        ]));
        $response->assertStatus(404);
        $response->assertJson([
            'meta' => [
                'status' => '404',
                'msg' => 'Forbidden'
            ]
        ]);
        Event::assertNotDispatched(ChatMessageEvent::class);
    }

    public function testSendingNonIntFromBlogID()
    {
        Event::fake();
        $response = $this->postJson($this->newMessageEndPoint, [
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
        Event::assertNotDispatched(ChatMessageEvent::class);
    }

    public function testSendingNotExistFromBlogID()
    {
        Event::fake();
        $lastBlogID = Blog::orderBy('id', 'desc')->first();
        if ($lastBlogID) {
            $lastBlogID = $lastBlogID->id + 10;
        } else {
            $lastBlogID = 10;
        }
        $response = $this->postJson($this->newMessageEndPoint, [
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
        Event::assertNotDispatched(ChatMessageEvent::class);
    }

    public function testSendingFromBlogIDDoesNotBelongToCurrentUser()
    {
        Event::fake();
        $response = $this->postJson($this->newMessageEndPoint, [
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
        Event::assertNotDispatched(ChatMessageEvent::class);
    }

    public function testSendingEmptyMessage()
    {
        Event::fake();
        $response = $this->postJson($this->newMessageEndPoint, [
            'from_blog_id' => $this->blogA->id,
        ], array_merge(Config::JSON, [
            'Authorization' => 'Bearer ' . $this->actA,
        ]));
        $response->assertStatus(422);
        $response->assertJson([
            'meta' => [
                'status' => '422',
                'msg' => 'Can\'t send empty message.'
            ]
        ]);
        Event::assertNotDispatched(ChatMessageEvent::class);
    }

    public function testSendingNonStringTextMessage()
    {
        Event::fake();
        $response = $this->postJson($this->newMessageEndPoint, [
            'from_blog_id' => $this->blogA->id,
            'text' => 23454,
        ], array_merge(Config::JSON, [
            'Authorization' => 'Bearer ' . $this->actA,
        ]));
        $response->assertStatus(422);
        $response->assertJson([
            'meta' => [
                'status' => '422',
                'msg' => 'The text must be a string.'
            ]
        ]);
        Event::assertNotDispatched(ChatMessageEvent::class);
    }

    public function testSendingTextMessage()
    {
        Event::fake();
        $response = $this->postJson($this->newMessageEndPoint, [
            'from_blog_id' => $this->blogA->id,
            'text' => 'this is new message'
        ], array_merge(Config::JSON, [
            'Authorization' => 'Bearer ' . $this->actA,
        ]));
        $response->assertStatus(200);
        $response->assertJsonStructure($this->newMessageResponseStructure);
        Event::assertDispatched(ChatMessageEvent::class, 1);
    }

    public function testSendingNonValidPhotoUrlMessage()
    {
        Event::fake();
        $response = $this->postJson($this->newMessageEndPoint, [
            'from_blog_id' => $this->blogA->id,
            'photo' => 'this is not a valid url'
        ], array_merge(Config::JSON, [
            'Authorization' => 'Bearer ' . $this->actA,
        ]));
        $response->assertStatus(422);
        $response->assertJson([
            'meta' => [
                'status' => '422',
                'msg' => 'The photo must be a valid URL.'
            ]
        ]);
        Event::assertNotDispatched(ChatMessageEvent::class);
    }

    public function testSendingPhotoMessage()
    {
        Event::fake();
        $response = $this->postJson($this->newMessageEndPoint, [
            'from_blog_id' => $this->blogA->id,
            'photo' => "https://images.chesscomfiles.com/uploads/v1/user/73738150.a35efadb.80x80o.50ecb169b9ec.png",
            ], array_merge(Config::JSON, [
            'Authorization' => 'Bearer ' . $this->actA,
        ]));
        $response->assertStatus(200);
        $response->assertJsonStructure($this->newMessageResponseStructure);
        Event::assertDispatched(ChatMessageEvent::class, 1);
    }

    public function testSendingNonValidGifUrlMessage()
    {
        Event::fake();
        $response = $this->postJson($this->newMessageEndPoint, [
            'from_blog_id' => $this->blogA->id,
            'gif' => 'this is not a valid url'
        ], array_merge(Config::JSON, [
            'Authorization' => 'Bearer ' . $this->actA,
        ]));
        $response->assertStatus(422);
        $response->assertJson([
            'meta' => [
                'status' => '422',
                'msg' => 'The gif must be a valid URL.'
            ]
        ]);
        Event::assertNotDispatched(ChatMessageEvent::class);
    }

    public function testSendingGifMessage()
    {
        Event::fake();
        $response = $this->postJson($this->newMessageEndPoint, [
            'from_blog_id' => $this->blogA->id,
            'gif' => "https://images.chesscomfiles.com/uploads/v1/user/73738150.a35efadb.80x80o.50ecb169b9ec.png",
            ], array_merge(Config::JSON, [
            'Authorization' => 'Bearer ' . $this->actA,
        ]));
        $response->assertStatus(200);
        $response->assertJsonStructure($this->newMessageResponseStructure);
        Event::assertDispatched(ChatMessageEvent::class, 1);
    }

    public function testSendingTextAndPhotoMessage()
    {
        Event::fake();
        $response = $this->postJson($this->newMessageEndPoint, [
            'from_blog_id' => $this->blogA->id,
            'text' => 'this is new message',
            'photo' => "https://images.chesscomfiles.com/uploads/v1/user/73738150.a35efadb.80x80o.50ecb169b9ec.png",
            ], array_merge(Config::JSON, [
            'Authorization' => 'Bearer ' . $this->actA,
        ]));
        $response->assertStatus(200);
        $response->assertJsonStructure($this->newMessageResponseStructure);
        Event::assertDispatched(ChatMessageEvent::class, 1);
    }

    public function testSendingTextAndGifMessage()
    {
        Event::fake();
        $response = $this->postJson($this->newMessageEndPoint, [
            'from_blog_id' => $this->blogA->id,
            'text' => 'this is new message',
            'gif' => "https://images.chesscomfiles.com/uploads/v1/user/73738150.a35efadb.80x80o.50ecb169b9ec.png",
            ], array_merge(Config::JSON, [
            'Authorization' => 'Bearer ' . $this->actA,
        ]));
        $response->assertStatus(200);
        $response->assertJsonStructure($this->newMessageResponseStructure);
        Event::assertDispatched(ChatMessageEvent::class, 1);
    }

    public function testSendingPhotoAndGifMessage()
    {
        Event::fake();
        $response = $this->postJson($this->newMessageEndPoint, [
            'from_blog_id' => $this->blogA->id,
            'photo' => "https://images.chesscomfiles.com/uploads/v1/user/73738150.a35efadb.80x80o.50ecb169b9ec.png",
            'gif' => "https://images.chesscomfiles.com/uploads/v1/user/73738150.a35efadb.80x80o.50ecb169b9ec.png",
            ], array_merge(Config::JSON, [
            'Authorization' => 'Bearer ' . $this->actA,
        ]));
        $response->assertStatus(422);
        $response->assertJson([
            'meta' => [
                'status' => '422',
                'msg' => 'Can\'t send image and gif at once.'
            ]
        ]);
        Event::assertNotDispatched(ChatMessageEvent::class);
    }

    public function testSendingTextAndPhotoAndGifMessage()
    {
        Event::fake();
        $response = $this->postJson($this->newMessageEndPoint, [
            'from_blog_id' => $this->blogA->id,
            'text' => 'this is new message',
            'photo' => "https://images.chesscomfiles.com/uploads/v1/user/73738150.a35efadb.80x80o.50ecb169b9ec.png",
            'gif' => "https://images.chesscomfiles.com/uploads/v1/user/73738150.a35efadb.80x80o.50ecb169b9ec.png",
            ], array_merge(Config::JSON, [
            'Authorization' => 'Bearer ' . $this->actA,
        ]));
        $response->assertStatus(422);
        $response->assertJson([
            'meta' => [
                'status' => '422',
                'msg' => 'Can\'t send image and gif at once.'
            ]
        ]);
        Event::assertNotDispatched(ChatMessageEvent::class);
    }
}
