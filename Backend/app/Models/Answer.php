<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

     /**
     * The table name this model refers to
     *
     * @var string
     */
    protected $table = 'answers';
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'ask_sender_blog_id',
        'ask_reciever_blog_id',
        'post_id',
        'ask_body',
        'anonymous_flag'
    ];
}
