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
    use RefreshDatabase;

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
    /**
     * Testing the authorization of the user to view follow tag relation
     *
     * @return void
     */
    public function testCanViewFollowRelation()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user]);
        $this->assertTrue($user->can('viewFollowRelation', [Tag::class, $blog]));
    }
    /**
     * Testing the non authorization of the user to view follow tag relation
     * A user is not allowed to view the follow relation status between a blog he don't own and any tag.
     *
     * @return void
     */
    public function testCanNotViewFollowRelation()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user]);
        $geustUser = User::factory()->create();
        $this->assertFalse($geustUser->can('viewFollowRelation', [Tag::class, $blog]));
    }
}
