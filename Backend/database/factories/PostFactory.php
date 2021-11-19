<?php

namespace Database\Factories;

use App\Models\Blog;
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
        $blogs = Blog::all();
        $available_blog_ids = $blogs->map(function ($blog) {
            return $blog->id;
        })->toArray();
        // print_r($available_blog_ids);
        return [
            'body' => $this->faker->randomHtml(4, 2),
            'blog_id' => $this->faker->randomElement($available_blog_ids)
        ];
    }
}
