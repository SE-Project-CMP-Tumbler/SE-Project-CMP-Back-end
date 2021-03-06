<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Blog extends Model
{
    use HasFactory;
    use Notifiable;

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
        'avatar_shape',
        'title',
        'replies_settings',
        'allow_messages',
        'allow_ask',
        'ask_page_title',
        'allow_anonymous_questions',
        'allow_submittions',
        'submissions_page_title',
        'submissions_guidelines',
        'user_id',
        'share_likes',
        'share_followings'
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
     * This is follow relation between blog and blog
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
    /**
     * Retrieve the list of blogs that the current blog is blocking.
     *
     * @return \Blog[]
     */
    public function blockings()
    {
        return $this->belongsToMany(Blog::class, 'blocks', 'blocker_id', 'blocked_id');
    }
    /**
     * Retrieve the list of blogs that have blocked the current blog.
     *
     * @return \Blog[]
     */
    public function blockers()
    {
        return $this->belongsToMany(Blog::class, 'blocks', 'blocked_id', 'blocker_id');
    }
    /**
    * the relation between blogs and chat rooms
    */
    public function chatRooms()
    {
        return $this->hasMany(ChatRooms::class, 'from_blog_id', 'id');
    }
    /**
    * TODO the relation between blogs and chat message
    */
    public function messages()
    {
        return $this->hasMany(ChatMessage::class, 'from_blog_username', 'username');
    }
    /**
    * the relation between blogs and replies
    */
    public function replies()
    {
        return $this->hasMany(Reply::class, 'blog_id', 'id');
    }
    /**
     * Retrieve list of submissions posts requested to the blog.
     *
     * @return \Post[]
     */
    public function submissionPosts()
    {
        return $this->belongsToMany(Post::class, 'submissions', 'reciever_id', 'post_id');
    }
    /**
     * Retrieve list of Questions requested to my blog.
     *
     * @return \Question[]
     */
    public function askedQuestions()
    {
        return $this->hasMany(Question::class, 'ask_reciever_blog_id', 'id');
    }
    /**
     * Retrieve list of answers to the previously asked questions I asked to other blogs (used in notification).
     *
     * @return \Answer[]
     */
    public function answers()
    {
        return $this->hasMany(Answer::class, 'ask_sender_blog_id', 'id');
    }
    /**
     * Retrieve list of submissions posts that have been approved by the blog.
     *
     * @return \Post[]
     */
    public function approvedSubmissionPosts()
    {
        return $this->hasMany(Post::class, 'approving_blog_id', 'id');
    }
    /** 
     * This is has relation between Theme and blog
     * @return Theme
     */
    public function theme()
    {
        return $this->hasOne(Theme::class);
    }
}
