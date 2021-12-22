<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FollowingOfBlogCollection extends ResourceCollection
{
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
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return ['followings' => FollowingBlogResource::Collection($this->collection, $this->followerId),
        'pagination' => [
            'total' => $this->total(),
            'count' => $this->count(),
            'per_page' => $this->perPage(),
            'current_page' => $this->currentPage(),
            'total_pages' => $this->lastPage(),
            'first_page_url' => $this->onFirstPage(),
            'last_page_url' => $this->lastPage(),
            'next_page_url' => $this->nextPageUrl(),
            'prev_page_url' => $this->previousPageUrl()
        ]
        ];
    }
}
