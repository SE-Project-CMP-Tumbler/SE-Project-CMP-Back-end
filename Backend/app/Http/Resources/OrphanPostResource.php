<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Blog;

class OrphanPostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $answer = ($this->type == 'answer') ? $this->answer()->first() : null;
        $askingBlog = ($answer && !$answer->anonymous_flag) ? Blog::find($answer->ask_sender_blog_id) : null;
        $blog = $this->blog;

        return [
            "post_id" => $this->id,
            "post_status" => $this->status,
            "post_time" => $this->published_at,
            "post_type" => $this->type,
            "post_body" => $this->body,
            "blog_id" => $this->blog_id,
            "blog_username" => $blog->username,
            "blog_avatar" => $blog->avatar,
            "blog_avatar_shape" => $blog->avatar_shape,
            "blog_title" => $blog->title,
            "question_flag" => ($askingBlog) ? false : true,
            "blog_username_asking" => ($askingBlog) ? $askingBlog->username : "",
            "blog_avatar_asking" => ($askingBlog) ? $askingBlog->avatar : "",
            "blog_avatar_shape_asking" => ($askingBlog) ? $askingBlog->avatar_shape : "",
            "blog_title_asking" => ($askingBlog) ? $askingBlog->title : "",
            "blog_id_asking" => ($askingBlog) ? $askingBlog->id : "",
            "question_body" => ($answer) ? $answer->ask_body : "",
        ];
    }
}
