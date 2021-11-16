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
     * This is like relation between post and blog
     * @return Post
     */
    public function post()
    {
        return $this->belongsToMany(Post::class);
    }
}
