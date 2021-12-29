<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReplyMentionBlog extends Model
{
    use HasFactory;

    /**
     * The table name this model refers to.
     *
     * @var string
     */
    protected $table = "reply_mention_blogs";

    /**
     * The incrementing state of the primary key.
     *
     * @var bool
     */
    public $incrementing = false;
    /**
     * The primary keys of the post_mention_blog table.
     *
     * @var string[]
     */
    protected $primaryKey = [
        'reply_id',
        'blog_id'
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'reply_id',
        'blog_id'
    ];
}
