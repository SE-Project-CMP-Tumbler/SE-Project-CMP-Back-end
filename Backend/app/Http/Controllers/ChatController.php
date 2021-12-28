<?php

namespace App\Http\Controllers;

use App\Http\Misc\Helpers\Config;
use App\Services\ChatService;
use App\Http\Resources\ChatMessageResource;
use App\Http\Resources\LastChatMessageCollection;
use App\Http\Resources\ChatSearchCollection;
use App\Http\Resources\ChatMessageCollection;
use App\Http\Requests\ChatMessageRequest;
use App\Http\Requests\ChatRoomRequest;
use App\Http\Requests\AllChatsRequest;
use App\Http\Requests\ChatSearchRequest;
use App\Http\Requests\NewChatMessageRequest;
use App\Models\Blog;

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
     *           @OA\Property(property="friend_id", type="integer", example=5),
     *           @OA\Property(property="friend_username", type="string", example="helloEveryWhere"),
     *           @OA\Property(property="friend_avatar", type="string", format="byte", example=""),
     *           @OA\Property(property="friend_avatar_shape", type="string", example=""),
     *           @OA\Property(property="friend_title", type="string", example=""),),
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

        return $this->generalResponse(new ChatSearchCollection($otherBlogs), "ok", "200");
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
     *       @OA\Property(property="chat_room_id", type="int", example="1452"),
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

        // other important checks are done in the service
        $chatRoomGID = (new ChatService())->chatRoomGIDService($request->from_blog_id, $request->to_blog_id);
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

        $lastMessages = (new ChatService())
            ->lastMessagesService($request->from_blog_id)
            ->paginate(Config::PAGINATION_LIMIT);
        return $this->generalResponse(new LastChatMessageCollection($lastMessages), "ok", "200");
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
        $messages = (new ChatService())->allMessagesService($request->chat_room_id, $request->from_blog_id);
        if ($messages) {
            // returned pagination link is broken :(
            // $messages = $messages->paginate(Config::PAGINATION_LIMIT);
            $messages = $messages->get();
            return $this->generalResponse(new ChatMessageCollection($messages), "ok", "200");
        } else {
            return $this->errorResponse("Forbidden", 403);
        }
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

        // this will return the error,code,null,
        // or either the null,null,message
        list($error, $code, $chatMessage) = (new ChatService())->sendMessageService(
            $request->chat_room_id,
            $request->from_blog_id,
            $request->text,
            $request->photo,
            $request->gif
        );
        if ($chatMessage) {
            $res = ["chat_messages" => [new ChatMessageResource($chatMessage)]];
            return $this->generalResponse($res, "ok");
        } else {
            return $this->errorResponse($error, $code);
        }
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

        $clearedState = (new ChatService())->clearChatService($request->chat_room_id, $request->from_blog_id);
        if ($clearedState) {
            return $this->generalResponse("chat cleared", "ok");
        } else {
            return $this->errorResponse('Forbidden', 404);
        }
    }
}
