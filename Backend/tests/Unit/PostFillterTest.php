<?php

namespace Tests\Unit;

use App\Http\Misc\Helpers\Config;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostFillterTest extends TestCase
{
    // use RefreshDatabase;

    /**
     * unti test for getting random posts
     *
     * @return void
     */
    public function testGetRandomPosts()
    {
        $post = Post::factory()->count(10)->create();
        $user = User::factory()->create();
        $response = $this->getJson('/api/posts/random_posts', [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ]);
        $response->assertStatus(200);
    }
}
