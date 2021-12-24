<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChatMessageResource;
use App\Http\Resources\LastChatMessageResource;
use App\Http\Resources\BlogResource;
use App\Http\Requests\ChatMessageRequest;
use App\Http\Requests\ChatRoomRequest;
use App\Http\Requests\AllChatsRequest;
use App\Http\Requests\ChatSearchRequest;
use App\Http\Requests\NewChatMessageRequest;
use App\Models\Blog;
use App\Models\ChatRoom;
use App\Models\ChatMessage;
use App\Events\ChatMessageEvent;
use App\Models\ChatRoomGID;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    /**
     * @OA\Post(
     *  path="/chat/chat_search",
     *  operationId="chatSearch",
     *  tags={"Chatting"},
     *  security={ {"bearer": {} }},
     *  description="retrieve all the blogs that start with given input",
     *  @OA\RequestBody(
     *   description="
     *     from_blog_id: the blog id that perform that search
     *     blog_username: the blog username that sends the message",
     *   @OA\JsonContent(
     *       @OA\Property(property="from_blog_id", type="int", example="82"),
     *       @OA\Property(property="blog_username", type="string", example="hello"),
     *     ),
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="Successful Retrieval",
     *    @OA\JsonContent(
     *     @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
     *      @OA\Property(property="response", type="object",
     *       @OA\Property(property="blogs", type="array",
     *          @OA\Items(
     *           @OA\Property(property="blog_id", type="integer", example=5),
     *           @OA\Property(property="blog_username", type="string", example="helloEveryWhere"),
     *           @OA\Property(property="blog_avatar", type="string", format="byte", example=""),
     *           @OA\Property(property="blog_avatar_shape", type="string", example=""),
     *           @OA\Property(property="blog_title", type="string", example=""),),
     *        ),
     *       ),
     *     ),
     *  @OA\Response(
     *    response=401,
     *    description="Unauthorized",
     *    @OA\JsonContent(
     *     @OA\Property(property="meta", type="object", example={"status": "401", "msg":"unauthorized"}),
     *     ),
     *  ),
     *  @OA\Response(
     *    response=403,
     *    description="Forbidden",
     *    @OA\JsonContent(
     *     @OA\Property(property="meta", type="object", example={"status": "403", "msg":"Forbidden"}),
     *     ),
     *  ),
     *  @OA\Response(
     *   response=404,
     *   description="Not Found",
     *   @OA\JsonContent(
     *      @OA\Property(property="meta", type="object", example={"status": "404", "msg":"Not Found"}),
     *   ),
     *  ),
     * )
     * )
     */

    /**
     * retreive all the blogs starting with the given input
     *
     * @param ChatRoomRequest $request
     * @return json
     **/
    public function chatSearch(ChatSearchRequest $request)
    {
        $request->validated();
        $otherBlogs = Blog::where('username', 'like', '%' . $request->blog_username . '%')
          ->Where('id', '!=', $request->from_blog_id)->get();

        $res = ["blogs" => []];
        foreach ($otherBlogs as $blog) {
            array_push($res["blogs"], new BlogResource($blog));
        }

        return $this->generalResponse($res, "ok", "200");
    }

    /**
     * @OA\Post(
     *  path="/chat/chat_id",
     *  operationId="getChatRoomGID",
     *  tags={"Chatting"},
     *  security={ {"bearer": {} }},
     *  description="retrieve the chat room id between two blogs",
     *  @OA\RequestBody(
     *   description="
     *   from_blog_id: the blog id that sends the message
     *   to_blog_id: the blog id to chat with",
     *   @OA\JsonContent(
     *       @OA\Property(property="from_blog_id", type="int", example="64"),
     *       @OA\Property(property="to_blog_id", type="int", example="73"),
     *     ),
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="Successful Retrieval",
     *    @OA\JsonContent(
     *     @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
     *      @OA\Property(property="response", type="object",
     *       @OA\Property(property="chat_room_id", type="string", example="1452"),
     *        ),
     *       ),
     *     ),
     *  @OA\Response(
     *    response=401,
     *    description="Unauthorized",
     *    @OA\JsonContent(
     *     @OA\Property(property="meta", type="object", example={"status": "401", "msg":"unauthorized"}),
     *     ),
     *  ),
     *  @OA\Response(
     *    response=403,
     *    description="Forbidden",
     *    @OA\JsonContent(
     *     @OA\Property(property="meta", type="object", example={"status": "403", "msg":"Forbidden"}),
     *     ),
     *  ),
     *  @OA\Response(
     *   response=404,
     *   description="Not Found",
     *   @OA\JsonContent(
     *      @OA\Property(property="meta", type="object", example={"status": "404", "msg":"Not Found"}),
     *   ),
     *  ),
     * )
     * )
     */

    /**
     * retreive the chatRoomGID
     *
     * @param ChatRoomRequest $request
     * @return json
     **/
    public function getChatRoomGID(ChatRoomRequest $request)
    {
        // this should check two things
        // 1. if the from_blog_username and to_blog_username are in the blogs table and they are different
        // 2. if the from_blog_username belongs to the current auth user
        $request->validated();

        // then get the chat_room_id if it exists already
        // and return the chat_room_gid that this chat_room belongs to
        $chatRoomOne = ChatRoom::whereIn('from_blog_id', [$request->from_blog_id, $request->to_blog_id])
        ->whereIn('to_blog_id', [$request->from_blog_id, $request->to_blog_id])->first();
        if ($chatRoomOne) {
            $chatRoomGID = ChatRoomGID::where('chat_room_one_id', $chatRoomOne->id)
            ->orWhere('chat_room_two_id', $chatRoomOne->id)->first()->id;
            return $this->generalResponse(["chat_room_id" => $chatRoomGID], "ok", "200");
        }

        // if we get to this point means that those two users is chatting for the first time
        // then create from->to room one
        $chatRoomOne = ChatRoom::create([
        "from_blog_id" => $request->from_blog_id,
        "to_blog_id" => $request->to_blog_id,
        "last_cleared_id" => 0,
        "last_sent_id" => 0,
        ]);

        // and create to->from room two
        $chatRoomTwo = ChatRoom::create([
        "from_blog_id" => $request->to_blog_id,
        "to_blog_id" => $request->from_blog_id,
        "last_cleared_id" => 0,
        "last_sent_id" => 0,
        ]);

        // and finally link them in the chatRoomGID
        $chatRoomGID = ChatRoomGID::create([
        "chat_room_one_id" => $chatRoomOne->id,
        "chat_room_two_id" => $chatRoomTwo->id,
        ]);

        return $this->generalResponse(["chat_room_id" => $chatRoomGID->id], "ok", "200");
    }

    /**
     * @OA\Post(
     *  path="/chat/all_chats",
     *  operationId="getAllChats",
     *  tags={"Chatting"},
     *  security={ {"bearer": {} }},
     *  description="retrieve all the last messages ok two blogs -- in the notification bar",
     *  @OA\RequestBody(
     *   description="
     *   from_blog_id: the blog id the user is using right now and default is its primary blog",
     *   required=false,
     *   @OA\JsonContent(
     *       @OA\Property(property="from_blog_id", type="int", example="64"),
     *     ),
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="
     *      Successful Retrieval
     *      blog: is for the sender
     *      freind: is for the receiver",
     *    @OA\JsonContent(
     *     @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
     *      @OA\Property(property="response", type="object",
     *        @OA\Property(property="chat_messages", type="array",
     *            @OA\Items(
     *             @OA\Property(property="text", type="string", example="hello world!"),
     *             @OA\Property(property="photo", type="string", example=""),
     *             @OA\Property(property="gif", type="string", example=""),
     *             @OA\Property(property="read", type="boolean", example="false"),
     *             @oA\property(property="blog_id", type="integer", example=5),
     *             @oA\property(property="blog_username", type="string", example="helloeverywhere"),
     *             @oA\property(property="blog_avatar", type="string", format="byte", example=""),
     *             @oA\property(property="blog_avatar_shape", type="string", example=""),
     *             @oA\property(property="blog_title", type="string", example=""),
     *             @oA\property(property="friend_id", type="integer", example=6),
     *             @oA\property(property="friend_username", type="string", example="cppmainblog"),
     *             @oA\property(property="friend_avatar", type="string", format="byte", example=""),
     *             @oA\property(property="friend_avatar_shape", type="string", example=""),
     *             @oA\property(property="friend_title", type="string", example=""),),),
     *         ),
     *        ),
     *  ),
     *  @OA\Response(
     *    response=401,
     *    description="Unauthorized",
     *    @OA\JsonContent(
     *     @OA\Property(property="meta", type="object", example={"status": "401", "msg":"unauthorized"}),
     *     ),
     *  ),
     *  @OA\Response(
     *    response=403,
     *    description="Forbidden",
     *    @OA\JsonContent(
     *     @OA\Property(property="meta", type="object", example={"status": "403", "msg":"Forbidden"}),
     *     ),
     *  ),
     *  @OA\Response(
     *   response=404,
     *   description="Not Found",
     *   @OA\JsonContent(
     *      @OA\Property(property="meta", type="object", example={"status": "404", "msg":"Not Found"}),
     *   ),
     *  ),
     * )
     * )
     */

    /**
     * retreive the last messages not deleted between the current blog and all other blogs
     *
     * @param Request $request
     * @return json
     **/
    public function getLastMessages(AllChatsRequest $request)
    {
        // validates that the entered {from_blog_username} belongs to the current user
        $request->validated();

        // select the user primary key unless the user provides one else
        $curUserBlogID = Blog::where('user_id', $request->user()->id)->pluck('id')->toArray()[0];
        if ($request->filled('from_blog_id')) {
            $curUserBlogID = [$request->from_blog_id];
        }

        // get all the user chatrooms {from->to, to->from} ids
        $userBlogChatRooms = ChatRoom::where('from_blog_id', $curUserBlogID)->get();

        $res = ["chat_messages" => []];
        foreach ($userBlogChatRooms as $chatRoom) {
            $lastchatRoomMessage = ChatMessage::where('id', $chatRoom->last_sent_id)->first();
            if ($lastchatRoomMessage) {
                array_push($res["chat_messages"], new LastChatMessageResource($lastchatRoomMessage));
            }
        }
        return $this->generalResponse($res, "ok", "200");
    }

    /**
     * @OA\Post(
     *  path="/chat/messages/{chat_room_id}",
     *  operationId="getAllMessages",
     *  tags={"Chatting"},
     *  security={ {"bearer": {} }},
     *  description="retrieve all messages sent between the current user blog and the other chat_participant",
     *  @OA\RequestBody(
     *   description="
     *   from_blog_id: the blog id the user is using right now and default is its primary blog",
     *   required=false,
     *   @OA\JsonContent(
     *       @OA\Property(property="from_blog_id", type="int", example="64"),
     *     ),
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="Successful Retrieval",
     *    @OA\JsonContent(
     *     @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
     *      @OA\Property(property="response", type="object",
     *        @OA\Property(property="chat_messages", type="array",
     *            @OA\Items(
     *             @OA\Property(property="text", type="string", example="hello world!"),
     *             @OA\Property(property="photo", type="string", example=""),
     *             @OA\Property(property="gif", type="string", example=""),
     *             @OA\Property(property="read", type="boolean", example="false"),
     *             @oA\property(property="blog_id", type="integer", example=5),
     *             @oA\property(property="blog_username", type="string", example="helloeverywhere"),
     *             @oA\property(property="blog_avatar", type="string", format="byte", example=""),
     *             @oA\property(property="blog_avatar_shape", type="string", example=""),
     *             @oA\property(property="blog_title", type="string", example=""),),),
     *         ),
     *        ),
     *  ),
     *  @OA\Response(
     *    response=401,
     *    description="Unauthorized",
     *    @OA\JsonContent(
     *     @OA\Property(property="meta", type="object", example={"status": "401", "msg":"unauthorized"}),
     *     ),
     *  ),
     *  @OA\Response(
     *    response=403,
     *    description="Forbidden",
     *    @OA\JsonContent(
     *     @OA\Property(property="meta", type="object", example={"status": "403", "msg":"Forbidden"}),
     *     ),
     *  ),
     *  @OA\Response(
     *   response=404,
     *   description="Not Found",
     *   @OA\JsonContent(
     *      @OA\Property(property="meta", type="object", example={"status": "404", "msg":"Not Found"}),
     *   ),
     *  ),
     * )
     * )
     */

    /**
     * get all chat messages between two blogs
     *
     * @param ChatMessageRequest $request
     * @return json
     **/
    public function getAllMessages(ChatMessageRequest $request)
    {
        $request->validated();

        // check if this id is in the table first -- done in the ChatMessageRequest
        // get the chatRooms which this id links
        $chatRooms = ChatRoomGID::where('id', $request->chat_room_id)->first();

        // check if the current auth user is one of the users in any of these chatRooms
        $chatRoomOne = $chatRooms->chatRoomOne()->first();
        $chatRoomTwo = $chatRooms->chatRoomTwo()->first();

        $curUserBlogsIDs = Blog::where('user_id', $request->user()->id)->pluck('id')->toArray();
        if ($request->filled('from_blog_id')) {
            $curUserBlogsIDs = [$request->from_blog_id];
        }

        $oneSender = $chatRoomOne->sender()->first()->id;
        $twoSender = $chatRoomTwo->sender()->first()->id;

        if (in_array($oneSender, $curUserBlogsIDs)) {
            $messages = ChatMessage::whereIn('chat_room_id', [$chatRoomOne->id, $chatRoomTwo->id])
            ->where('id', '>', $chatRoomOne->last_cleared_id)->get();
        } elseif (in_array($twoSender, $curUserBlogsIDs)) {
            $messages = ChatMessage::whereIn('chat_room_id', [$chatRoomOne->id, $chatRoomTwo->id])
            ->where('id', '>', $chatRoomTwo->last_cleared_id)->get();
        } else {
            return $this->errorResponse("Forbidden", "403");
        }

        $arr = ["chat_messages" => []];
        foreach ($messages as $item) {
            array_push($arr["chat_messages"], new ChatMessageResource($item));
        }

        // returned pagination link is broken :(
        // ->paginate(Config::PAGINATION_LIMIT);
        // return $this->generalResponse(new ChatMessageCollection($res), "ok", "200");
        return $this->generalResponse($arr, "ok", "200");
    }

    /**
     * @OA\Post(
     *  path="/chat/new_message/{chat_room_id}",
     *  operationId="sendMessage",
     *  tags={"Chatting"},
     *  security={ {"bearer": {} }},
     *  description="send messages to the other chat_participant.",
     *  @OA\RequestBody(
     *   description="
     *   send one of the following types {text | gif | photo | text + gif | text + photo}
     *   text: send a text message or empty
     *   gif: the url of the sent gif
     *   photo: the url of the sent photo
     *   from_blog_id: the blog id the user is using right now and default is its primary blog",
     *   @OA\JsonContent(
     *    @OA\Property(property="text", type="string", example="hello, how are you?"),
     *    @OA\Property(property="photo", type="string", format="byte", example=""),
     *   ),
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="Successful Retrieval",
     *    @OA\JsonContent(
     *     @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
     *      @OA\Property(property="response", type="object",
     *        @OA\Property(property="chat_messages", type="array",
     *            @OA\Items(
     *             @OA\Property(property="text", type="string", example="hello world!"),
     *             @OA\Property(property="photo", type="string", example=""),
     *             @OA\Property(property="gif", type="string", example=""),
     *             @OA\Property(property="read", type="boolean", example="false"),
     *             @oA\property(property="blog_id", type="integer", example=5),
     *             @oA\property(property="blog_username", type="string", example="helloeverywhere"),
     *             @oA\property(property="blog_avatar", type="string", format="byte", example=""),
     *             @oA\property(property="blog_avatar_shape", type="string", example=""),
     *             @oA\property(property="blog_title", type="string", example=""),),),
     *         ),
     *        ),
     *  ),
     *  @OA\Response(
     *    response=401,
     *    description="Unauthorized",
     *    @OA\JsonContent(
     *     @OA\Property(property="meta", type="object", example={"status": "401", "msg":"unauthorized"}),
     *    ),
     *  ),
     *  @OA\Response(
     *    response=403,
     *    description="Forbidden",
     *    @OA\JsonContent(
     *     @OA\Property(property="meta", type="object", example={"status": "403", "msg":"Forbidden"}),
     *     ),
     *  ),
     *  @OA\Response(
     *   response=404,
     *   description="Not Found",
     *   @OA\JsonContent(
     *      @OA\Property(property="meta", type="object", example={"status": "404", "msg":"Not Found"}),
     *   ),
     *  ),
     * )
     */

    /**
     * send a new message from one blog to another
     * this message can be of the following types {text | gif | photo | text + gif | text + photo}
     *
     * @param NewChatMessageRequest $request
     * @return json
     **/
    public function sendMessage(NewChatMessageRequest $request)
    {
        $request->validated();

        // check if this id is in the table first -- done in the ChatMessageRequest
        // get the chatRooms which this id links
        $chatRooms = ChatRoomGID::where('id', $request->chat_room_id)->first();

        // check if the current auth user is one of the users in any of these chatRooms
        $chatRoomOne = $chatRooms->chatRoomOne()->first();
        $chatRoomTwo = $chatRooms->chatRoomTwo()->first();

        $curUserBlogID = Blog::where('user_id', $request->user()->id)->first()->id;
        if ($request->filled('from_blog_id')) {
            $curUserBlogID = $request->from_blog_id;
        }

        $oneSender = $chatRoomOne->sender()->first()->id;
        $twoSender = $chatRoomTwo->sender()->first()->id;

        // this means that the user can't create new chat_room_gid, chat_room record
        // in the table with this newMessage request
        if ($curUserBlogID == $oneSender) {
            $roomID = $chatRoomOne->id;
        } elseif ($curUserBlogID == $twoSender) {
            $roomID = $chatRoomTwo->id;
        } else {
            return $this->errorResponse("Forbidden", "403");
        }

        $hasText = $request->filled('text');
        $hasImageUrl = $request->filled('photo');
        $hasGifUrl = $request->filled('gif');

        // sending empty message
        if (!$hasText && !$hasImageUrl && !$hasGifUrl) {
            return $this->errorResponse("Can't send empty message.");
        }

        // sending both image and gif and this covers the 3-types at once also
        if ($hasImageUrl && $hasGifUrl) {
            return $this->errorResponse("Can't send image and gif at once.");
        }

        $chatMessage = ChatMessage::create([
        "chat_room_id" => $roomID,
        "text" => $request->text,
        "image_url" => $request->photo,
        "gif_url" => $request->gif,
        "read" => false,
        ]);

        ChatRoom::whereIn('id', [$chatRoomOne->id, $chatRoomTwo->id])->update([
        'last_sent_id' => $chatMessage->id,
        ]);

        broadcast(new ChatMessageEvent($chatMessage, $request->chat_room_id))->toOthers();
        $res = ["chat_messages" => [new ChatMessageResource($chatMessage)]];
        return $this->generalResponse($res, "ok");
    }

    /**
     * @OA\Delete(
     *  path="/chat/clear_chat/{chat_room_id}",
     *  operationId="clearChatRoom",
     *  tags={"Chatting"},
     *  security={ {"bearer": {} }},
     *  description="delete all messages in the chat room between this blog and the other chat_participant",
     *  @OA\RequestBody(
     *   description="
     *   from_blog_id: the blog id the user is using right now and default is its primary blog",
     *   required=false,
     *   @OA\JsonContent(
     *       @OA\Property(property="from_blog_id", type="int", example="75"),
     *     ),
     *  ),
     *  @OA\Response(
     *   response=200,
     *   description="Successful Operation",
     *   @OA\JsonContent(
     *    @OA\Property(property="meta", type="object", example={"status": "200", "msg":"ok"}),
     *   ),
     *  ),
     *  @OA\Response(
     *    response=401,
     *    description="Unauthorized",
     *    @OA\JsonContent(
     *     @OA\Property(property="meta", type="object", example={"status": "401", "msg":"unauthorized"}),
     *    ),
     *  ),
     *  @OA\Response(
     *    response=403,
     *    description="Forbidden",
     *    @OA\JsonContent(
     *     @OA\Property(property="meta", type="object", example={"status": "403", "msg":"Forbidden"}),
     *     ),
     *  ),
     *  @OA\Response(
     *   response=404,
     *   description="Not Found",
     *   @OA\JsonContent(
     *      @OA\Property(property="meta", type="object", example={"status": "404", "msg":"Not Found"}),
     *   ),
     *  ),
     * )
     */

    /**
     * delete a message between two blogs by its id
     *
     * @param ChatMessageRequest $request
     * @return json
     **/
    public function clearChat(ChatMessageRequest $request)
    {
        $request->validated();

        // check if this id is in the table first -- done in the ChatMessageRequest
        // get the chatRooms which this id links
        $chatRooms = ChatRoomGID::where('id', $request->chat_room_id)->first();

        // check if the current auth user is one of the users in any of these chatRooms
        $chatRoomOne = $chatRooms->chatRoomOne()->first();
        $chatRoomTwo = $chatRooms->chatRoomTwo()->first();

        $curUserBlogID = Blog::where('user_id', $request->user()->id)->first()->id;
        if ($request->filled('from_blog_id')) {
            $curUserBlogID = $request->from_blog_id;
        }

        $oneSender = $chatRoomOne->sender()->first()->id;
        $twoSender = $chatRoomTwo->sender()->first()->id;

        // check which of these chat_room should we update its last_cleared_id value
        if ($curUserBlogID == $oneSender) {
            $roomID = $chatRoomOne->id;
        } elseif ($curUserBlogID == $twoSender) {
            $roomID = $chatRoomTwo->id;
        } else {
            return $this->errorResponse("Forbidden", "403");
        }

        // get the last chat_message_id between those two users
        $lastMessage = ChatMessage::whereIn('chat_room_id', [$chatRoomOne->id, $chatRoomTwo->id])
            ->orderBy('id', 'desc')->first();

        if ($lastMessage) {
            // update the last cleared id of the $roomID
            ChatRoom::where('id', $roomID)->update([
                'last_cleared_id' => $lastMessage->id,
                'last_sent_id' => 0,
            ]);
        }
        return $this->generalResponse("chat cleared", "ok");
    }
}
