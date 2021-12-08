<?php

namespace App\Services;

use App\Http\Misc\Helpers\Config;
use App\Http\Resources\PostResource;
use App\Models\Post;

class PostFillterService
{
    /**
     * get all the posts of that given type
     *
     * @param string|null $postType passing null will return all the posts
     * @return array
     **/
    public function getPostsOfType(string $postType = null)
    {
        $res = ["posts" => []];
        $isValidType = false;
        if ($postType == null || $postType == "") {
            $allRandomPosts = Post::all();
            foreach ($allRandomPosts as $item) {
                array_push($res["posts"], new PostResource($item));
            }
        } else {
            foreach (Config::POST_TYPES as $type) {
                if ($postType == $type) {
                    $isValidType = true;
                    break;
                }
            }
            if ($isValidType) {
                $allPosts = Post::where([
                    ['type', '=', $postType],
                ])->get();
                foreach ($allPosts as $item) {
                    array_push($res["posts"], new PostResource($item));
                }
            }
        }
        return $res;
    }

    /**
     * get all the posts in random order
     *
     * @return array
     **/
    public function getRandomPostService()
    {
        $res = $this->getPostsOfType();
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
        # TODO: apply the notes filter to get the more trending ones
        $res = $this->getPostsOfType();
        shuffle($res["posts"]);
        return $res;
    }

    /**
     * get a radar post
     *
     * @return array
     **/
    public function getRadarPostService()
    {
        $res = $this->getPostsOfType();
        shuffle($res["posts"]);
        $res = ["posts" => array_slice($res["posts"], 0, 1)];
        return $res;
    }
}
