<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    use HasFactory;

     protected $fillable = [
        'from_blog_username',
        'to_blog_username',
        'last_cleared_id',
        'last_sent_id',
     ];

     # TODO
     public function chatRoomGID()
     {
         return $this->belongsTo(ChatRoomGID::class, 'id', 'chat_room_one_id');
     }

     public function sender()
     {
         return $this->belongsTo(Blog::class, 'from_blog_username', 'username');
     }

     public function receiver()
     {
         return $this->belongsTo(Blog::class, 'to_blog_username', 'username');
     }

     public function messages()
     {
         return $this->hasMany(ChatMessage::class);
     }
}
