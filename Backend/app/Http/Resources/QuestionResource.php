<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Blog;

class QuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $askingBlog = ($this->anonymous_flag) ? null : Blog::find($this->ask_sender_blog_id);
        return [
            "blog_username" => ($askingBlog) ? $askingBlog->username : "",
            "blog_avatar" => ($askingBlog) ? $askingBlog->avatar : "",
            "blog_avatar_shape" => ($askingBlog) ? $askingBlog->avatar_shape : "",
            "blog_title" => ($askingBlog) ? $askingBlog->title : "",
            "blog_id" => ($askingBlog) ? $askingBlog->id : "",
            "question_body" => $this->body,
            "question_id" => $this->id,
            "question_flag" => $this->anonymous_flag,
            "ask_time" => $this->created_at,
            "post_type" => "ask"
        ];
    }
}
