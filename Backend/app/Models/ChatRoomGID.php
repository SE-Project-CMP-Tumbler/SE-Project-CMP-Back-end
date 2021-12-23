<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * this model to relate ChatRoom and ChatMessage relations
 */
class ChatRoomGID extends Model
{
    use HasFactory;

     protected $fillable = [
        'chat_room_one_id',
        'chat_room_two_id',
     ];

     protected $table = "chat_room_gids";

     public function chatRoomOne()
     {
         return $this->hasOne(ChatRoom::class, 'id', 'chat_room_one_id');
     }

     public function chatRoomTwo()
     {
         return $this->hasOne(ChatRoom::class, 'id', 'chat_room_two_id');
     }
}
