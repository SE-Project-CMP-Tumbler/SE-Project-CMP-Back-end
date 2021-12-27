<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    use HasFactory;
    /**
      * The attributes that are mass assignable.
      *
      * @var string[]
    */
    protected $fillable = [
        'color_title',
        'font_title',
        'font_weight_title',
        'background_color',
        'body_font',
        'blog_id'
    ];
     /**
     * @return BelongsTo
     * @description Get the blog that owns the theme
     */
    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }
}
