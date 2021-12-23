<?php

namespace Database\Factories;

use App\Models\Blog;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChatRoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $fromBlog = Blog::factory()->create();
        $toBlog = Blog::factory()->create();
        return [
            "from_blog_id" => $fromBlog->id,
            "to_blog_id" => $toBlog->id,
            "last_cleared_id" => 0,
            "last_sent_id" => 0,
        ];
    }
}
