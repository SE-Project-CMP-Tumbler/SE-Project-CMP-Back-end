<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TagPolicyTest extends TestCase
{
    /**
     * Testing the authorization to create a tag
     *
     * @return void
     */
    public function testCanCreateTag()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        $post = Post::factory()->create(['blog_id' => $blog->id]);

        $this->assertTrue($user->can('create', [Tag::class, $post]));
    }
    /**
     * Testing the non authorization to create a tag
     *
     * @return void
     */
    public function testCanNotCreateTag()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        $post = Post::factory()->create(['blog_id' => $blog->id]);
        $userGuest = User::factory()->create();
        $this->assertFalse($userGuest->can('create', [Tag::class, $post]));
    }
}
