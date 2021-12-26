<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Reply;
use App\Models\Like;
use App\Models\Blog;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // $question = Question::where(['answer_id' => $this->id])
        // $quesion->sender->username
        $like_status = false;
        $user = Auth('api')->user();
        if ($user) {
            $blog_id =  Blog::where([['user_id',$user->id],['is_primary', true]])->first()->id;
            $like_status = ((Like::where([['blog_id', $blog_id] , ['post_id', $this->id]])->first()) != null);
        }
        $blog = $this->blog;
        return [
            "post_id" => $this->id,
            "post_status" => $this->status,
            "pinned" => $this->pinned,
            "post_time" => $this->published_at,
            "post_type" => $this->type,
            "post_body" => $this->body,
            "blog_id" => $this->blog_id,
            "blog_username" => $blog->username,
            "blog_avatar" => $blog->avatar,
            "blog_avatar_shape" => $blog->avatar_shape,
            "blog_title" => $blog->title,
            "blog_username_asking" => "",
            "blog_avatar_asking" => "",
            "blog_avatar_shape_asking" => "",
            "blog_title_asking" => "",
            "notes_count" => (Reply::where('post_id', $this->id)->count() + Like::where('post_id', $this->id)->count()),
            "is_liked" => $like_status
        ];
    }
}
