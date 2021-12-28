<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\ChatMessage;
use App\Http\Resources\ChatMessageResource;

class ChatMessageEvent implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public $chatMessage;
    public $chatRoomGID;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ChatMessage $chatMessage, int $chatRoomGID)
    {
        $this->chatMessage = $chatMessage;
        $this->chatRoomGID = $chatRoomGID;
    }

    public function broadcastAs()
    {
        return 'chat-update';
    }

    public function broadcastWith()
    {
        $sender = $this->chatMessage->chatRoom()->first()->sender;
        return [
            "text" => $this->chatMessage->text,
            "photo" => $this->chatMessage->image_url,
            "gif" => $this->chatMessage->gif_url,
            "read" => $this->chatMessage->read,
            "from_blog_username" => $sender->username,
            "from_blog_id" => $sender->id,
            "from_blog_avatar" => $sender->avatar,
            "from_blog_avatar_shape" => $sender->avatar_shape,
            "from_blog_title" => $sender->title,
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // $arr = array(
        //     $this->chatMessage->chat_room->from_blog_username,
        //     $this->chatMessage->chat_room->to_blog_username
        // );
        // sort($arr);
        // return new PrivateChannel('channel-' . $arr[0]  . '-' . $arr[1]);
        // return new PrivateChannel('channel-' . $this->chatRoomGID);
        return new Channel('channel-' . $this->chatRoomGID);
    }
}
