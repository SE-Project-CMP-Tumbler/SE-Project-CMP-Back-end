<?php

namespace App\Services;

use App\Http\Resources\PostResource;
use App\Models\Post;

class PostFillterService
{
    /**
     * get all the posts in random order
     *
     * @return array
     **/
    public function getRandomPostService()
    {
        $res = ["posts" => []];
        $allRandomPosts = Post::all();
        foreach ($allRandomPosts as $item) {
            array_push($res["posts"], new PostResource($item));
        }
        shuffle($res["posts"]);
        return $res;
    }

    /**
     * get all the trending posts in random order
     *
     * @return array
     **/
    public function getTrendingPostService()
    {
    }
}
