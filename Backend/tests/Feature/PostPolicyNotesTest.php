<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Blog;
use App\Models\Post;
use App\Models\User;
use App\Models\Like;
use App\Models\Reply;

class PostPolicyNotesTest extends TestCase
{
    /**
     * test true can reply on post
     *
     * @return void
     */
    public function testTrueCanReplyPostPolicy()
    {
        $ReplierBlog = Blog::factory()->create(['is_primary' => true]);
        $blog = Blog::factory()->create();
        $post = Post::factory()->create(['blog_id' => $blog->id]);
        $this->assertTrue($ReplierBlog->user->can('canReply', $post));
    }
    /**
     * test false can reply on post
     *
     * @return void
     */
    public function testFalseCanReplyPostPolicy()
    {
        $ReplierBlog = Blog::factory()->create(['is_primary' => true]);
        $blog = Blog::factory()->create(['replies_settings' => 'Only Tumblrs you follow can reply']);
        $post = Post::factory()->create(['blog_id' => $blog->id]);
        $this->assertFalse($ReplierBlog->user->can('canReply', $post));
    }
    /**
     * test true can like on post
     *
     * @return void
     */
    public function testTrueCanLikePostPolicy()
    {
        $LikerBlog = Blog::factory()->create(['is_primary' => true]);
        $blog = Blog::factory()->create();
        $post = Post::factory()->create(['blog_id' => $blog->id]);
        $this->assertTrue($LikerBlog->user->can('canLike', $post));
    }
    /**
     * test false can like on post
     *
     * @return void
     */
    public function testFalseCanLikePostPolicy()
    {
        $LikerBlog = Blog::factory()->create(['is_primary' => true]);
        $blog = Blog::factory()->create();
        $post = Post::factory()->create(['blog_id' => $blog->id]);
        Like::create([
            'post_id' => $post->id,
            'blog_id' => $LikerBlog->id
        ]);
        $this->assertFalse($LikerBlog->user->can('canLike', $post));
    }
    /**
     * test true can unlike on post
     *
     * @return void
     */
    public function testTrueDeleteLikePostPolicy()
    {
        $LikerBlog = Blog::factory()->create(['is_primary' => true]);
        $blog = Blog::factory()->create();
        $post = Post::factory()->create(['blog_id' => $blog->id]);
        $like = Like::create([
            'post_id' => $post->id,
            'blog_id' => $LikerBlog->id
        ]);
        $this->assertTrue($LikerBlog->user->can('canDeleteLike', [Post::class, $like]));
    }
    /**
     * test false can unlike on post
     *
     * @return void
     */
    public function testFalseDeleteLikePostPolicy()
    {
        $user = Blog::factory()->create(['is_primary' => true])->user;
        $LikerBlog = Blog::factory()->create(['is_primary' => true]);
        $blog = Blog::factory()->create();
        $post = Post::factory()->create(['blog_id' => $blog->id]);
        $like = Like::create([
            'post_id' => $post->id,
            'blog_id' => $LikerBlog->id
        ]);
        $this->assertFalse($user->can('canDeleteLike', [Post::class, $like]));
    }
    /**
     * test true can delete reply on post
     *
     * @return void
     */
    public function testTrueDeleteReplyPostPolicy()
    {
        $ReplierBlog = Blog::factory()->create(['is_primary' => true]);
        $blog = Blog::factory()->create();
        $post = Post::factory()->create(['blog_id' => $blog->id]);
        $reply = Reply::create([
            'post_id' => $post->id,
            'blog_id' => $ReplierBlog->id,
            'description' => "<div><p>hey</p></div>"
        ]);
        $this->assertTrue($ReplierBlog->user->can('canDeleteReply', [Post::class, $reply]));
    }
    /**
     * test false can not delete reply on post
     *
     * @return void
     */
    public function testFalseDeleteReplyPostPolicy()
    {
        $user = Blog::factory()->create(['is_primary' => true])->user;
        $ReplierBlog = Blog::factory()->create(['is_primary' => true]);
        $blog = Blog::factory()->create();
        $post = Post::factory()->create(['blog_id' => $blog->id]);
        $reply = Reply::create([
            'post_id' => $post->id,
            'blog_id' => $ReplierBlog->id,
            'description' => "<div><p>hey</p></div>"
        ]);
        $this->assertFalse($user->can('canDeleteReply', [Post::class, $reply]));
    }
}
