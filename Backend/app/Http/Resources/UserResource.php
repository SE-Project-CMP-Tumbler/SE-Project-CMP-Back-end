<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //$blog =Blog::where('user_id', $this->id)->first();
        return [
            'id' => $this->id,
            //'blog_username' => $blog->blog_username,
            'email' => $this->email,
            //'blog_avatar' => $blog->avatar,
            'access_token' =>  $this->token(),
        ];
    }
}
