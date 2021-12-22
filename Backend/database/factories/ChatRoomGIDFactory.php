<?php

namespace Database\Factories;

use App\Models\ChatRoom;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChatRoomGIDFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $charRoomOne = ChatRoom::factory()->create();
        $charRoomTwo = ChatRoom::create([
            "from_blog_username" => $charRoomOne->to_blog_username,
            "to_blog_username" => $charRoomOne->from_blog_username,
            "last_cleared_id" => 0,
            "last_sent_id" => 0,
        ]);
        return [
            "chat_room_one_id" => $charRoomOne->id,
            "chat_room_two_id" => $charRoomTwo->id,
        ];
    }
}
