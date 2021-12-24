<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LastChatMessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $sender = $this->chatRoom()->first()->sender;
        $receiver = $this->chatRoom()->first()->receiver;
        return [
            "text" => $this->text,
            "photo" => $this->image_url,
            "gif" => $this->gif_url,
            "read" => $this->read,

            "from_blog_username" => $sender->username,
            "from_blog_id" => $sender->id,
            "from_blog_avatar" => $sender->avatar,
            "from_blog_avatar_shape" => $sender->avatar_shape,
            "from_blog_title" => $sender->title,

            "friend_blog_username" => $receiver->username,
            "friend_blog_id" => $receiver->id,
            "friend_blog_avatar" => $receiver->avatar,
            "friend_blog_avatar_shape" => $receiver->avatar_shape,
            "friend_blog_title" => $receiver->title,
        ];
    }
}
