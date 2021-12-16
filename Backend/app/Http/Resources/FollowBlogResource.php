<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FollowBlogResource extends JsonResource
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
            "blog_avatar" => $this->avatar,
            "blog_avatar_shape" =>  $this->avatar_shape,
            "blog_username" => $this->username,
            "blog_id" => $this->id
        ];
    }
}
