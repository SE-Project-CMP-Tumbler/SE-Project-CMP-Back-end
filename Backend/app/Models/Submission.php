<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    /**
     * The table name this model refers to
     *
     * @var string
     */
    protected $table = 'submissions';
    /**
     * The primary keys of the submissions table
     *
     * @var string[]
     */
    protected $primaryKey = ['submitter_id', 'reciever_id', 'post_id'];
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
        'submitter_id',
        'reciever_id',
        'post_id'
    ];
    /**
     * Get the post related to a submission.
     *
     * @return \Post
     */
    public function post()
    {
        return $this->hasOne(Post::class, 'id', 'post_id');
    }
    /**
     * Get the reciever related to a submission.
     *
     * @return \Post
     */
    public function reciever()
    {
        return $this->belongsTo(Blog::class, 'reciever_id', 'id');
    }
}
