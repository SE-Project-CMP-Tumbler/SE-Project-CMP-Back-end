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
     * The relation of getting all posts belonging to the blog
     * @return Post[]
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
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
}
