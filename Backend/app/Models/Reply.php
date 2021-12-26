<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use HasFactory;

     /**
     * The table name this model refers to
     *
     * @var string
     */
    protected $table = 'replies';
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'post_id',
        'blog_id',
        'description'
    ];
    /**
    * the relation between blogs and replies
    */
    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }
    /**
    * the relation between posts and replies
    */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
