<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Blog;
use App\Models\Post;
use App\Models\User;
use App\Models\FollowBlog;
use App\Models\Like;
use App\Models\Reply;

class PostPolicyNotesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * test true 1 can reply on post
     * replying on a 'Everyone can reply' blog's post
     * 
     * @return void
     */
    public function testTrue1CanReplyPostPolicy()
    {
        $ReplierBlog = Blog::factory()->create(['is_primary' => true]);
        $blog = Blog::factory()->create(['is_primary' => true, 'replies_settings' => 'Everyone can reply']);
        $post = Post::factory()->create(['blog_id' => $blog->id]);
        $this->assertTrue($ReplierBlog->user->can('canReply', $post));
    }
    /**
     * test true 2 can reply on post
     * replying on a 'Only Tumblrs you follow can reply' blog's post and the owner blog follows me
     * 
     * @return void
     */
    public function testTrue2CanReplyPostPolicy()
    {
        $ReplierBlog = Blog::factory()->create(['is_primary' => true]);
        $blog = Blog::factory()->create(['is_primary' => true, 'replies_settings' => 'Only Tumblrs you follow can reply']);
        FollowBlog::create(['follower_id' => $blog->id , 'followed_id' => $ReplierBlog->id]);
        $post = Post::factory()->create(['blog_id' => $blog->id]);
        $this->assertTrue($ReplierBlog->user->can('canReply', $post));
    }
    /**
     * test true 3 can reply on post
     * replying on a 'Tumblrs you follow and Tumblrs following you for a week can reply' blog's post and I followed the owner blog from less than a week
     * 
     * @return void
     */
    public function testTrue3CanReplyPostPolicy()
    {
        $ReplierBlog = Blog::factory()->create(['is_primary' => true]);
        $blog = Blog::factory()->create(['replies_settings' => 'Tumblrs you follow and Tumblrs following you for a week can reply']);
        FollowBlog::create(['follower_id' => $ReplierBlog->id , 'followed_id' => $blog->id]);
        $post = Post::factory()->create(['blog_id' => $blog->id]);
        $this->assertTrue($ReplierBlog->user->can('canReply', $post));
    }
    /**
     * test true 4 can reply on post
     * replying on a 'Tumblrs you follow and Tumblrs following you for a week can reply' blog's post and the owner blog followed me from less than a week
     * 
     * @return void
     */
    public function testTrue4CanReplyPostPolicy()
    {
        $ReplierBlog = Blog::factory()->create(['is_primary' => true]);
        $blog = Blog::factory()->create(['replies_settings' => 'Tumblrs you follow and Tumblrs following you for a week can reply']);
        FollowBlog::create(['follower_id' => $blog->id , 'followed_id' => $ReplierBlog->id]);
        $post = Post::factory()->create(['blog_id' => $blog->id]);
        $this->assertTrue($ReplierBlog->user->can('canReply', $post));
    }
    /**
     * test false 1 can reply on post
     * tring to reply on a 'Only Tumblrs you follow can reply' blog's post and the owner blog doesn't follow me 
     *
     * @return void
     */
    public function testFalse1CanReplyPostPolicy()
    {
        $ReplierBlog = Blog::factory()->create(['is_primary' => true]);
        $blog = Blog::factory()->create(['replies_settings' => 'Only Tumblrs you follow can reply']);
        $post = Post::factory()->create(['blog_id' => $blog->id]);
        $this->assertFalse($ReplierBlog->user->can('canReply', $post));
    }
        /**
     * test false 2 can reply on post
     * tring to reply on a 'Only Tumblrs you follow can reply' blog's post and the owner blog doesn't follow me although I followed him
     *
     * @return void
     */
    public function testFalse2CanReplyPostPolicy()
    {
        $ReplierBlog = Blog::factory()->create(['is_primary' => true]);
        $blog = Blog::factory()->create(['replies_settings' => 'Only Tumblrs you follow can reply']);
        FollowBlog::create(['follower_id' => $ReplierBlog->id , 'followed_id' => $blog->id]);
        $post = Post::factory()->create(['blog_id' => $blog->id]);
        $this->assertFalse($ReplierBlog->user->can('canReply', $post));
    }
    /**
     * test false 3 can reply on post
     * tring to reply on a 'Tumblrs you follow and Tumblrs following you for a week can reply' blog's post that I have followed 8 days ago 
     * 
     * @return void
     */
    public function testFalse3CanReplyPostPolicy()
    {
        $ReplierBlog = Blog::factory()->create(['is_primary' => true]);
        $blog = Blog::factory()->create(['replies_settings' => 'Tumblrs you follow and Tumblrs following you for a week can reply']);
        $follow = FollowBlog::factory()->create(['follower_id' => $ReplierBlog->id , 'followed_id' => $blog->id, 'created_at' => now()->addDay(8), 'updated_at' => now()->addDay(8)]);
        $post = Post::factory()->create(['blog_id' => $blog->id]);
        $this->assertFalse($ReplierBlog->user->can('canReply', $post));
    }
    /**
     * test false 4 can reply on post
     * tring to reply on a 'Tumblrs you follow and Tumblrs following you for a week can reply' blog's post that have followed me from 8 days 
     * 
     * @return void
     */
    public function testFalse4CanReplyPostPolicy()
    {
        $ReplierBlog = Blog::factory()->create(['is_primary' => true]);
        $blog = Blog::factory()->create(['replies_settings' => 'Tumblrs you follow and Tumblrs following you for a week can reply']);
        $follow = FollowBlog::factory()->create(['follower_id' => $blog->id , 'followed_id' => $ReplierBlog->id, 'created_at' => now()->addDay(8), 'updated_at' => now()->addDay(8)]);
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
