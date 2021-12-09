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
        'pinned'
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
     * Get the tags belonging to this post
     *
     * @return \Tag[]
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tag', 'post_id', 'tag_description');
    }
}
