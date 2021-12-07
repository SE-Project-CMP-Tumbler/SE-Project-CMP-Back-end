<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FollowBlog extends Model
{
    use HasFactory;

      /**
     * The table name this model refers to
     *
     * @var string
     */
    protected $table = 'follow_blog';
    /**
     * The primary keys of the follow_blog table
     *
     * @var string[]
     */
    protected $primaryKey = ['follower_id', 'followed_id'];
    /**
     * The incrementing state of the primary key
     *
     * @var bool
     */
    public $incrementing = false;
    /**
     * The attributes that are mass assignable
     *
     * @var string
     */
    protected $fillable = [
        'follwer_id',
        'followed_id'
    ];
}
