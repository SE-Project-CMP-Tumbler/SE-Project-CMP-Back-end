<?php

namespace Database\Factories;

use App\Models\Blog;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
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
        return [
            'ask_sender_blog_id' => $senderBlog->id,
            'ask_reciever_blog_id' => $recieverBlog->id,
            'body' =>  ('<p>' . $this->faker->paragraph() . '</p>'),
            'anonymous_flag' => $this->faker->boolean(),
        ];
    }
}
