<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatSearchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'friend_id' => $this->id,
            'friend_username' => $this->username,
            'friend_avatar' => $this->avatar,
            'friend_avatar_shape' => $this->avatar_shape,
            'friend_title' => $this->title,
        ];
    }
}
