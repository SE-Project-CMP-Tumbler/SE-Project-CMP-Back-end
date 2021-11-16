<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostTag extends Model
{
    use HasFactory;

    /**
     * The table name this model refers to
     *
     * @var string
     */
    protected $table = 'post_tag';
    /**
     * The primary keys of the post_tag table
     *
     * @var string
     */
    protected $primaryKey = ['post_id', 'tag_description'];
    /**
     * The incrementing state of the primary key
     */
    public $incrementing = false;
    /**
     * The attributes that are mass assignable
     *
     * @var string
     */
    protected $fillable = ['post_id','tag_description'];


}
