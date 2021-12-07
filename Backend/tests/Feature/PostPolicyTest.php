<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostPolicyTest extends TestCase
{
    /**
     * Testing the authorization of a user to create a post
     *
     * @return void
     */
    public function testTrueCreatePost()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        $this->assertTrue($user->can('create', [Post::class, $blog]));
    }
    /**
     * Testing the non authorization of a user to create a post
     *
     * @return void
     */
    public function testFalseCreatePost()
    {
        $user = User::factory()->create();
        $userGuest = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        $this->assertFalse($userGuest->can('create', [Post::class, $blog]));
    }
    /**
     * Testing the authorization of a user to edit a post
     *
     * @return void
     */
    public function testTrueUpdatePost()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        $post = Post::factory()->create(['blog_id' => $blog->id]);
        $this->assertTrue($user->can('update', [Post::class, $post]));
    }
    /**
     * Testing the non authorization of a user to edit a post
     *
     * @return void
     */
    public function testFalseUpdatePost()
    {
        $user = User::factory()->create();
        $userGuest = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        $post = Post::factory()->create(['blog_id' => $blog->id]);
        $this->assertFalse($userGuest->can('update', [Post::class, $post]));
    }
    /**
     * Testing the authorization of a user to delete a post
     *
     * @return void
     */
    public function testTrueDeletePost()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        $post = Post::factory()->create(['blog_id' => $blog->id]);
        $this->assertTrue($user->can('delete', [Post::class, $post]));
    }
    /**
     * Testing the non authorization of a user to edit a post.
     *
     * @return void
     */
    public function testFalseDeletePost()
    {
        $user = User::factory()->create();
        $userGuest = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        $post = Post::factory()->create(['blog_id' => $blog->id]);
        $this->assertFalse($userGuest->can('delete', [Post::class, $post]));
    }
    /**
     * Testing the authorization of a user to get draft posts.
     *
     * @return void
     */
    public function testTrueGetDraftPosts()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        $post = Post::factory()->create([
            'blog_id' => $blog->id,
            'status' => 'draft'
        ]);
        $this->assertTrue($user->can('viewDraftPosts', [Post::class, $blog]));
    }
    /**
     * Testing the non authorization of a user to get draft posts.
     *
     * @return void
     */
    public function testFalseGetDraftPosts()
    {
        $user = User::factory()->create();
        $userGuest = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        $post = Post::factory()->create([
            'blog_id' => $blog->id,
            'status' => 'draft'
        ]);
        $this->assertFalse($userGuest->can('viewDraftPosts', [Post::class, $blog]));
    }
}
