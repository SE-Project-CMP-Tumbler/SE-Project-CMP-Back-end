<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'blog_id',
        'body',
        'status',
        'type',
        'published_at',
        'pinned',
        'approving_blog_id',
        'parent_id'
    ];
    /**
     * Get the blog that owns the Post
     *
     * @return \Blog
     */
    public function blog()
    {
        return $this->belongsTo(Blog::class, 'blog_id', 'id');
    }
    /**
     * Get the blog that approved the Post if it were once a submission post on him.
     * Posts that weren't a submission, will have null approver.
     *
     * @return \Blog
     */
    public function approver()
    {
        return $this->belongsTo(Blog::class, 'approving_blog_id', 'id');
    }

    /**
     * Get the tags belonging to this post
     *
     * @return \Tag[]
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tag', 'post_id', 'tag_description');
    }

    /**
     * Get the Blogs mentioned in this post's content
     *
     * @return \Blog[]
     */
    public function mentionedBlogs()
    {
        return $this->belongsToMany(Blog::class, 'post_mention_blog', 'post_id', 'blog_id');
    }
    /**
     * Get the List of Blogs liked this post.
     *
     * @return \Blog[]
     */
    public function postLikers()
    {
        return $this->belongsToMany(Blog::class, 'likes', 'post_id', 'blog_id');
    }
    /**
     * Get the List of Blogs liked this post.
     *
     * @return \Blog[]
     */
    public function likes($limit)
    {
        return Like::where('post_id', $this->id)->latest()->paginate($limit);
    }
    /**
    * the relation between post and replies
    */
    public function replies()
    {
        return $this->hasMany(Reply::class, 'post_id', 'id');
    }
    /**
    * the relation between post and replies
    */
    public function answer()
    {
        return $this->hasOne(Answer::class, 'post_id', 'id');
    }
    /**
     * Get a post's parent post
     */
    public function parentPost()
    {
        return $this->belongsTo(Post::class, 'parent_id', 'id');
    }
}
