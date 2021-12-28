<?php

namespace Database\Factories;

use App\Models\Blog;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnswerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $senderBlog = Blog::factory()->create();
        $recieverBlog = Blog::factory()->create();
        $post =  Post::factory()->create(['type' => 'answer']);
        return [
            'ask_sender_blog_id' => $senderBlog->id,
            'ask_reciever_blog_id' => $recieverBlog->id,
            'post_id' =>  $post->id,
            'anonymous_flag' => $this->faker->boolean(),
            'ask_body' =>  ('<p>' . $this->faker->paragraph() . '</p>'),
        ];
    }
}
