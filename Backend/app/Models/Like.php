<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

     /**
     * The table name this model refers to
     *
     * @var string
     */
    protected $table = 'likes';
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'post_id',
        'blog_id'
    ];
      /**
     * The primary keys of the like table
     *
     * @var string[]
     */
    protected $primaryKey = ['post_id', 'blog_id'];
    /**
     * The incrementing state of the primary key
     *
     * @var bool
     */
    public $incrementing = false;
}
