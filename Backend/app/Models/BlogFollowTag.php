<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogFollowTag extends Model
{
    use HasFactory;

    /**
     * The table name this model refers to.
     *
     * @var string
     */
    protected $table = 'blog_follow_tag';
    /**
     * The primary keys of the blog_follow_tag table.
     *
     * @var string[]
     */
    protected $primaryKey = ['blog_id', 'tag_description'];
    /**
     * The incrementing state of the primary keys.
     */
    public $incrementing = false;
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'blog_id',
        'tag_description'
    ];
}
