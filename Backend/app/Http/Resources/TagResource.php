<?php

namespace App\Http\Resources;

use App\Services\BlogService;
use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $blogService = new BlogService();
        $isFollowed = $blogService->checkIsFollowingTag($this->description);
        return [
            'tag_description' => $this->description,
            'tag_image' => $this->image,
            'posts_count' => $this->posts_count,
            "followed" => $isFollowed,
            "followers_number" => $this->followers()->count()
        ];
    }
}
