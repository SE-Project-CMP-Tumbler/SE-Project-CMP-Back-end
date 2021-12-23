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
        return new PrivateChannel('channel-' . $this->chatRoomGID);
    }
}
