<?php

namespace App\Http\Resources;

use App\Services\BlogService;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
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
    public function __construct($resource, $followerId)
    {
        // Ensure you call the parent constructor
        parent::__construct($resource);
        $this->resource = $resource;
        $this->followerId = $followerId;
    }
    public function toArray($request)
    {
        $blogService = new BlogService();
        if ($this->followerId != null) {
            $check = $blogService->checkIsFollowed($this->followerId, $this->id);
        } else {
            $check = false;
        }

        return [
            'id' => $this->id,
            'username' => $this->username,
            'avatar' => $this->avatar,
            'avatar_shape' => $this->avatar_shape,
            'header_image' => $this->header_image,
            'title' => $this->title,
            'description' => $this->description,
            'is_primary' => $this->is_primary,
            'allow_ask' => $this->allow_ask ,
            'allow_submittions' => $this->allow_submittions ,
            'share_likes' => $this->share_likes,
            'share_followings' => $this->share_followings,
            'followed' => $check
        ];
    }
}
