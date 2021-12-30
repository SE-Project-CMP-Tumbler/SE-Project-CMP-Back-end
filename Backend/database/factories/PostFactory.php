<?php

namespace Database\Factories;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id ]);
        return [
            'body' => '<p>' . $this->faker->paragraph() . '</p>' ,
            'blog_id' => $blog->id,
            'published_at' => $this->faker->dateTime()
        ];
    }
}
