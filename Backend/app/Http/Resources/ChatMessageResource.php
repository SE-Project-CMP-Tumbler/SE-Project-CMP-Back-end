<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatMessageResource extends JsonResource
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
        return [
            "text" => $this->text,
            "photo" => $this->image_url,
            "gif" => $this->gif_url,
            "read" => $this->read,
            "blog_username" => $sender->username,
            "blog_id" => $sender->id,
            "blog_avatar" => $sender->avatar,
            "blog_avatar_shape" => $sender->avatar_shape,
            "blog_title" => $sender->title,
        ];
    }
}
