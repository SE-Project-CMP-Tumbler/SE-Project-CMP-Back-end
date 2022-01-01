<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Blog;
use App\Models\User;
use App\Models\Question;

class BlogPolicyAskTest extends TestCase
{
    use RefreshDatabase;

    /**
     * test true can ask blog
     *
     * @return void
     */
    public function testTruecanAskBlogPolicy()
    {
        $blogReciever = Blog::factory()->create(['allow_ask' => true]);
        $this->assertTrue($blogReciever->user->can('canAsk', $blogReciever));
    }
    /**
     * test false can ask blog
     *
     * @return void
     */
    public function testFalsecanAskBlogPolicy()
    {
        $blogReciever = Blog::factory()->create(['allow_ask' => false]);
        $this->assertFalse($blogReciever->user->can('canAsk', $blogReciever));
    }
    /**
     * test true can ask Anonymously blog
     *
     * @return void
     */
    public function testTruecanAskAnonymousBlogPolicy()
    {
        $blogReciever = Blog::factory()->create(['allow_anonymous_questions' => true]);
        $this->assertTrue($blogReciever->user->can('canAskAnonymous', $blogReciever));
    }
    /**
     * test false can ask Anonymous blog
     *
     * @return void
     */
    public function testFalsecanAskAnonymousBlogPolicy()
    {
        $blogReciever = Blog::factory()->create(['allow_anonymous_questions' => false]);
        $this->assertFalse($blogReciever->user->can('canAskAnonymous', $blogReciever));
    }
    /**
     * test true can view messages
     *
     * @return void
     */
    public function testTrueViewMessagesBlogPolicy()
    {
        $blog = Blog::factory()->create();
        $this->assertTrue($blog->user->can('viewMessages', $blog));
    }
    /**
     * test false can view messages
     *
     * @return void
     */
    public function testFalseViewMessagesBlogPolicy()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create();
        $this->assertFalse($user->can('viewMessages', $blog));
    }
    /**
     * test true can delete messages
     *
     * @return void
     */
    public function testTrueDeleteMessagesBlogPolicy()
    {
        $blog = Blog::factory()->create();
        $this->assertTrue($blog->user->can('deleteMessages', $blog));
    }
    /**
     * test false can delete messages
     *
     * @return void
     */
    public function testFalseDeleteMessagesBlogPolicy()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create();
        $this->assertFalse($user->can('deleteMessages', $blog));
    }
}
