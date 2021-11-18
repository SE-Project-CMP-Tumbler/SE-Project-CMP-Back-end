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
            'username' => $blog->username,
            'email' => $this->email,
            'blog_avatar' => $blog->avatar,
            'access_token' =>  $this->token(),
        ];
    }
}
