<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

     protected $fillable = [
        'chat_room_id',
        'text',
        'image_url',
        'gif_url',
        'read',
     ];

     public function chatRoom()
     {
         return $this->belongsTo(ChatRoom::class, 'chat_room_id', 'id');
     }
}
