<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BlogPolicyTest extends TestCase
{

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
    }
}
