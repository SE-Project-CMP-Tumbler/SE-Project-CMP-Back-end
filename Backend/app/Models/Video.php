<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'width',
        'height',
        'size',
        'duration',
        'audio_codec',
        'video_codec',
        'preview_image_url'
    ];
}
