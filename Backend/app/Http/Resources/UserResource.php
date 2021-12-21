<?php

namespace App\Http\Resources;

use App\Models\Blog;
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
        $blog = Blog::where([['user_id',$this->id],['is_primary', true]])->first();

        return [
            'id' => $this->id,
            'blog_id' => $blog->id,
            'blog_username' => $blog->username,
            'email' => $this->email,
            'is_verified' => ($this->email_verified_at != null),
            'blog_avatar' => $blog->avatar,
            'access_token' =>  $this->token(),
        ];
    }
}
