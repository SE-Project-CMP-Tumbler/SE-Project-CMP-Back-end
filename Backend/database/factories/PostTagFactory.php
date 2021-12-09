<?php

namespace Database\Factories;

use App\Models\Blog;
use App\Models\Post;
use App\Models\PostTag;
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
        $post = Post::factory()->create();
        $tag = Tag::factory()->create();
        return [
            'post_id' => $post->id,
            'tag_description' => $tag['description']
        ];
    }
}
