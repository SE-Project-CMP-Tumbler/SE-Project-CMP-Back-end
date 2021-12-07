<?php

namespace Database\Factories;

use App\Models\Blog;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

class BlogFollowTagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // $blogIds = Blog::all('id')->map(function ($id) {
        //     return $id;
        // });
        // $tagDecriptions = Tag::all('description')->map(function ($description) {
        //     return $description;
        // });

        $blog = Blog::factory()->create();
        $tag = Tag::factory()->create();
        return [
            'blog_id' => $blog->id,
            'tag_description' => $tag->description
        ];
    }
}
