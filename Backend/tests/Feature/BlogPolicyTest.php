<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BlogPolicyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A  test update true policy of blog
     *
     * @return void
     */
    public function testUpdateTrueBlogPolicy()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        $this->assertTrue($user->can('update', $blog));
        $user->delete();
        $blog->delete();
    }
    /**
     * A  test update false policy of blog
     *
     * @return void
     */
    public function testUpdateFalseBlogPolicy()
    {
        $user = User::factory()->create();
        $userGuest = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        $this->assertFalse($userGuest->can('update', $blog));
        $user->delete();
        $blog->delete();
    }
    /**
     * A  test update false policy of blog
     *
     * @return void
     */
    public function testDeleteFalseBlogPolicy()
    {
        $user = User::factory()->create();
        $userGuest = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        $this->assertFalse($userGuest->can('delete', $blog));
        $user->delete();
        $blog->delete();
        $userGuest->delete();
    }
      /**
     * A delete policy of blog
     *
     * @return void
     */
    public function testDeleteBlogPolicy()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        $this->assertTrue($user->can('delete', $blog));
        $user->delete();
        $blog->delete();
    }
      /**
     * A  test View true policy of blog
     *
     * @return void
     */
    public function testViewTrueBlogPolicy()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        $this->assertTrue($user->can('view', $blog));
        $user->delete();
        $blog->delete();
    }
    /**
     * A  test update false policy of blog
     *
     * @return void
     */
    public function testViewFalseBlogPolicy()
    {
        $user = User::factory()->create();
        $userGuest = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        $this->assertFalse($userGuest->can('view', $blog));
        $user->delete();
        $blog->delete();
        $userGuest->delete();
    }
      /**
     * A  test Share likes false policy of blog
     *
     * @return void
     */
    public function testShareLikesFalsePolicy()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id ,'share_likes'  => false]);
        $userGuest = User::factory()->create();
        $blogGuest = Blog::factory()->create(['user_id' => $userGuest->id ]);
        $this->assertFalse($user->can('shareLikes', $blogGuest));
        $user->delete();
        $blog->delete();
        $userGuest->delete();
        $blogGuest->delete();
    }
    /**
     * A  test Share likes true policy of blog
     *
     * @return void
     */
    public function testShareLikesTruePolicy()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id ,'share_likes'  => true]);
        $userGuest = User::factory()->create();
        $blogGuest = Blog::factory()->create(['user_id' => $userGuest->id ]);
        $this->assertFalse($user->can('shareLikes', $blogGuest));
        $user->delete();
        $blog->delete();
        $userGuest->delete();
        $blogGuest->delete();
    }
    /**
     * A  test Share followings false policy of blog
     *
     * @return void
     */
    public function testShareFollowingsFalsePolicy()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id ,'share_likes'  => false]);
        $userGuest = User::factory()->create();
        $blogGuest = Blog::factory()->create(['user_id' => $userGuest->id ]);
        $this->assertFalse($user->can('shareFollowings', $blogGuest));
        $user->delete();
        $blog->delete();
        $userGuest->delete();
        $blogGuest->delete();
    }
    /**
     * A  test Share followings true policy of blog
     *
     * @return void
     */
    public function testShareFollowingsTruePolicy()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id ,'share_likes'  => true]);
        $userGuest = User::factory()->create();
        $blogGuest = Blog::factory()->create(['user_id' => $userGuest->id ]);
        $this->assertFalse($user->can('shareFollowings', $blogGuest));
        $user->delete();
        $blog->delete();
        $userGuest->delete();
        $blogGuest->delete();
    }
}
