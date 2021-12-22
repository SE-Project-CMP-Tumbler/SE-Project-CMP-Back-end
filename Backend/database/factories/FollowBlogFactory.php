<?php

namespace Database\Factories;

use App\Models\Blog;
use App\Models\User;
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
        $followerUser = User::factory()->create();
        $follower = Blog::factory()->create(['user_id' => $followerUser->id]);
        $followedUser = User::factory()->create();
        $followed = Blog::factory()->create(['user_id' => $followedUser->id]);
        return [
            'follower_id' => $follower->id,
            'followed_id' => $followed->id
        ];
    }
}
