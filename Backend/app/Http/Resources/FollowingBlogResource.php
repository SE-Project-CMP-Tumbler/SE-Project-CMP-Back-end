<?php

namespace App\Http\Resources;

use App\Services\BlogService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;

class FollowingBlogResource extends JsonResource
{
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
        if (Auth::user() != null) {
            $primaryBlog = $blogService->getPrimaryBlog(Auth::user());
        } else {
            $primaryBlog = null;
        }
        if ($primaryBlog != null) {
            $check = $blogService->checkIsFollowed($primaryBlog->id, $this->id);
        } else {
            $check = false;
        }

        return [
            "blog_avatar" => $this->avatar,
            "blog_avatar_shape" =>  $this->avatar_shape,
            "blog_username" => $this->username,
            "blog_id" => $this->id,
            "is_followed" => $check
        ];
    }
}
