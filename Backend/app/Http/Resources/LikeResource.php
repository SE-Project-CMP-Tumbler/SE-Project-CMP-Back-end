<?php

namespace App\Http\Resources;

use App\Services\BlogService;
use App\Models\Blog;
use Illuminate\Http\Resources\Json\JsonResource;

class LikeResource extends JsonResource
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
        $blogService = new BlogService();
        if (Auth('api')->user() != null) {
            $primaryBlog = $blogService->getPrimaryBlog(Auth('api')->user());
        } else {
            $primaryBlog = null;
        }

        if ($primaryBlog != null) {
            $check = $blogService->checkIsFollowed($primaryBlog->id, $this->blog_id);
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
            'followed' => $check
        ];
    }
}
