<?php

namespace App\Services;

use App\Models\Blog;
use App\Models\ChatRoom;
use App\Models\ChatMessage;
use App\Events\ChatMessageEvent;
use App\Models\ChatRoomGID;

class ChatService
{
    /**
     * get the chat id/gid for the two user
     *
     * @param int $fromBlogID
     * @param int $toBlogID
     * @return ChatRoomGID
     **/
    public function chatRoomGIDService(int $fromBlogID, int $toBlogID)
    {
        // get the chat_room_id if it exists already
        // and return the chat_room_gid that this chat_room belongs to
        $chatRoomOne = ChatRoom::whereIn('from_blog_id', [$fromBlogID, $toBlogID])
        ->whereIn('to_blog_id', [$fromBlogID, $toBlogID])->first();
        if ($chatRoomOne) {
            $chatRoomGID = ChatRoomGID::where('chat_room_one_id', $chatRoomOne->id)
            ->orWhere('chat_room_two_id', $chatRoomOne->id)->first();
        } else {
            // if we get to this point means that those two users is chatting for the first time
            // then create from->to room one
            $chatRoomOne = ChatRoom::create([
                "from_blog_id" => $fromBlogID,
                "to_blog_id" => $toBlogID,
                "last_cleared_id" => 0,
                "last_sent_id" => 0,
            ]);

            // and create to->from room two
            $chatRoomTwo = ChatRoom::create([
                "from_blog_id" => $toBlogID,
                "to_blog_id" => $fromBlogID,
                "last_cleared_id" => 0,
                "last_sent_id" => 0,
            ]);

            // and finally link them in the chatRoomGID
            $chatRoomGID = ChatRoomGID::create([
                "chat_room_one_id" => $chatRoomOne->id,
                "chat_room_two_id" => $chatRoomTwo->id,
            ]);
        }
        return $chatRoomGID;
    }

    /**
     * retreive all the last messagess between cur blog and other blogs
     *
     * @param int $fromBlogID to decide which of the current users blogs to get its last messages
     * @return collection
     **/
    public function lastMessagesService(int $fromBlogID = null)
    {
        // select the user primary key unless the user provides one else
        $curUserBlogID = Blog::where('user_id', auth()->user()->id)->pluck('id')->toArray()[0];
        if ($fromBlogID) {
            $curUserBlogID = [$fromBlogID];
        }
        // get all the user chatrooms {from->to, to->from} ids
        $lastSentIDs = ChatRoom::where('from_blog_id', $curUserBlogID)->pluck('last_sent_id')->toArray();
        $lastMessages = ChatMessage::whereIn('id', $lastSentIDs);

        return $lastMessages;
    }

    /**
     * get all messages between two blogs
     *
     * @param int $chatRoomID to reterive messages from
     * @param int $fromBlogID to decide which of the current users blogs to get its last messages
     * @return collection
     **/
    public function allMessagesService(int $chatRoomID, int $fromBlogID = null)
    {
        // check if this id is in the table first -- done in the ChatMessageRequest
        // get the chatRooms which this id links
        $chatRooms = ChatRoomGID::where('id', $chatRoomID)->first();

        // check if the current auth user is one of the users in any of these chatRooms
        $chatRoomOne = $chatRooms->chatRoomOne()->first();
        $chatRoomTwo = $chatRooms->chatRoomTwo()->first();

        $curUserBlogsIDs = Blog::where('user_id', auth()->user()->id)->pluck('id')->toArray();
        if ($fromBlogID) {
            $curUserBlogsIDs = [$fromBlogID];
        }

        $oneSender = $chatRoomOne->sender()->first()->id;
        $twoSender = $chatRoomTwo->sender()->first()->id;
        $last_cleared_id = -1;

        if (in_array($oneSender, $curUserBlogsIDs)) {
            $last_cleared_id = $chatRoomOne->last_cleared_id;
        } elseif (in_array($twoSender, $curUserBlogsIDs)) {
            $last_cleared_id = $chatRoomTwo->last_cleared_id;
        } else {
            return null;
        }

        $messages = ChatMessage::whereIn('chat_room_id', [$chatRoomOne->id, $chatRoomTwo->id])
            ->where('id', '>', $last_cleared_id);

        return $messages;
    }

    /**
     * send messages from one blog to the another
     *
     * @param int $chatRoomID to reterive messages from
     * @param int $fromBlogID to decide which of the current users blogs to get its last messages
     * @param int $text the message text body
     * @param int $photo a link to the photo associated with this message
     * @param int $gif a link to a gif associated with this message
     * @return array
     **/
    public function sendMessageService(
        int $chatRoomID,
        int $fromBlogID = null,
        $text = null,
        $photo = null,
        $gif = null
    ) {
        // check if this id is in the table first -- done in the ChatMessageRequest
        // get the chatRooms which this id links
        $chatRooms = ChatRoomGID::where('id', $chatRoomID)->first();

        // check if the current auth user is one of the users in any of these chatRooms
        $chatRoomOne = $chatRooms->chatRoomOne()->first();
        $chatRoomTwo = $chatRooms->chatRoomTwo()->first();

        $curUserBlogID = Blog::where('user_id', auth()->user()->id)->first()->id;
        if ($fromBlogID) {
            $curUserBlogID = $fromBlogID;
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
            return ["Forbidden", 404, null];
        }

        // sending empty message
        if (!$text && !$photo && !$gif) {
            return ["Can't send empty message.", 422, null];
        }

        // sending both image and gif and this covers the 3-types at once also
        if ($photo && $gif) {
            return ["Can't send image and gif at once.", 422, null];
        }

        $chatMessage = ChatMessage::create([
            "chat_room_id" => $roomID,
            "text" => $text,
            "image_url" => $photo,
            "gif_url" => $gif,
            "read" => false,
        ]);

        ChatRoom::whereIn('id', [$chatRoomOne->id, $chatRoomTwo->id])->update([
            'last_sent_id' => $chatMessage->id,
        ]);

        broadcast(new ChatMessageEvent($chatMessage, $chatRoomID))->toOthers();
        return [null, null, $chatMessage];
    }

    /**
     * clear the chat room
     *
     * @param int $chatRoomID to reterive messages from
     * @param int $fromBlogID to decide which of the current users blogs to get its last messages
     * @return boolean
     * @throws conditon
     **/
    public function clearChatService(int $chatRoomID, int $fromBlogID = null)
    {
        // check if this id is in the table first -- done in the ChatMessageRequest
        // get the chatRooms which this id links
        $chatRooms = ChatRoomGID::where('id', $chatRoomID)->first();

        // check if the current auth user is one of the users in any of these chatRooms
        $chatRoomOne = $chatRooms->chatRoomOne()->first();
        $chatRoomTwo = $chatRooms->chatRoomTwo()->first();

        $curUserBlogID = Blog::where('user_id', auth()->user()->id)->first()->id;
        if ($fromBlogID) {
            $curUserBlogID = $fromBlogID;
        }

        $oneSender = $chatRoomOne->sender()->first()->id;
        $twoSender = $chatRoomTwo->sender()->first()->id;

        // check which of these chat_room should we update its last_cleared_id value
        if ($curUserBlogID == $oneSender) {
            $roomID = $chatRoomOne->id;
        } elseif ($curUserBlogID == $twoSender) {
            $roomID = $chatRoomTwo->id;
        } else {
            return false;
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
            return true;
        }
        return false;
    }
}
