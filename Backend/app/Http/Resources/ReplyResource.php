<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\BlogService;
use App\Models\Blog;

class ReplyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
      /**
     * Create a new resource instance.
     *
     * @param  mixed  $resource
     * @return void
     */
    public function __construct($resource)
    {
        // Ensure you call the parent constructor
        parent::__construct($resource);
        $this->resource = $resource;
    }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($request->user()) {
            $primaryBlogId = Blog::where([['user_id',$request->user()->id],['is_primary', true]])->first()->id;
        } else {
            $primaryBlogId = false;
        }
        $blogService = new BlogService();
        if ($this->blog_id && $primaryBlogId) {
            $check = $blogService->checkIsFollowed($this->blog_id, $primaryBlogId);
        } else {
            $check = false;
        }
        $blog = Blog::find($this->blog_id);
        return [
            "blog_avatar" => $blog->avatar,
            "blog_avatar_shape" => $blog->avatar_shape,
            "blog_username" => $blog->username,
            "blog_title" => $blog->title,
            'blog_id' => $this->blog_id,
            'followed' => $check,
            "reply_id" => $this->id,
            "reply_time" => $this->updated_at,
            "reply_text" => $this->description
        ];
    }
}
