<?php

namespace Database\Factories;

use App\Models\Blog;
use Illuminate\Database\Eloquent\Factories\Factory;

class FollowBlogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $follower = Blog::factory()->create();
        $followed = Blog::factory()->create();
        return [
            'follower_id' => $follower->id,
            'followed_id' => $followed->id
        ];
    }
}
