<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    /**
     * The primary key of the tags table
     *
     * @var string
     */
    protected $primaryKey = 'description';
    /**
     * The data type of the tags' table primary key.
     *
     * @var string
     */
    protected $keyType = 'string';
    /**
     * The incrementing state of the primary key
     *
     * @var bool
     */
    public $incrementing = false;
    /**
      * The attributes that are mass assignable.
      *
      * @var string[]
      */
    protected $fillable = [
      'description',
      'image'
    ];

    /**
     * Get posts having and attached to this tag
     *
     * @return \Post[]
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_tag', 'tag_description', 'post_id');
    }
}
