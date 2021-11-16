<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostTagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $posts = Post::all();
        $tags = Tag::all();

        $available_post_ids = $posts->map(function ($post) {
            return $post->id;
        });
        $available_tag_descriptions = $tags->map(function ($tag) {
            return $tag->description;
        });

        return [
            'post_id' => $this->faker->randomElement($available_post_ids),
            'tag_description' => $this->faker->randomElement($available_tag_descriptions)
        ];
    }
}
