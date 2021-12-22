<?php

namespace Database\Factories;

use App\Models\ChatRoom;
use App\Models\ChatRoomGID;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChatMessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // just for fast testing
        // $from_blog_username = "helloblog";
        // $to_blog_username = "cppmainblog";
        // $charRoomOne = ChatRoom::create([
        //     "from_blog_username" => $from_blog_username,
        //     "to_blog_username" => $to_blog_username,
        //     "last_cleared_id" => 0,
        // ]);

        $charRoomOne = ChatRoom::factory()->create();
        $charRoomTwo = ChatRoom::create([
            "from_blog_username" => $charRoomOne->to_blog_username,
            "to_blog_username" => $charRoomOne->from_blog_username,
            "last_cleared_id" => 0,
            "last_sent_id" => 0,
        ]);
        $chatRoomGID = ChatRoomGID::create([
            "chat_room_one_id" => $charRoomOne->id,
            "chat_room_two_id" => $charRoomTwo->id,
        ]);
        $randChatRoom = rand(0, 10) < 5 ? $charRoomOne : $charRoomTwo;
        return [
            "chat_room_id" => $randChatRoom->id,
            "text" => $this->faker->text(),
            "image_url" => null,
            "gif_url" => null,
            "read" => false,
        ];
    }
}
