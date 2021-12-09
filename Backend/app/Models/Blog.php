<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'username',
        'is_primary',
        'password',
        'description',
        'avatar',
        'header_image',
        'title',
        'replies_settings',
        'allow_messages',
        'allow_ask',
        'ask_page_title',
        'allow_anonymous_questions',
        'allow_submittions',
        'submissions_page_title',
        'submissions_guidelines',
        'user_id'
    ];
    /**
     * This is has relation between user and blog
     * @return User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    /**
     * The relation of getting all posts that the blog liked
     * @return Post[]
     */
    public function likes()
    {
        return $this->belongsToMany(Post::class, 'likes', 'blog_id', 'post_id');
    }
    /**
     * This is follow relation between post and blog
     * @return Blog
     */
    public function followers()
    {
        return $this->belongsToMany(Blog::class, 'follow_blog', 'followed_id', 'follower_id');
    }
     /**
     * This is follow relation between blog and blog
     * @return Blog
     */
    public function followings()
    {
        return $this->belongsToMany(Blog::class, 'follow_blog', 'follower_id', 'followed_id');
    }
    /**
     * The relation of getting all tags followed by the blog.
     *
     * @return Tag[]
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'blog_follow_tag', 'blog_id', 'tag_description');
    }
    /**
     * The relation of getting all posts belonging to the blog
     * @return Post[]
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
