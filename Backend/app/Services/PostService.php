<?php

namespace App\Services;

use App\Models\Post;

class PostService
{
    public function createPost($data)
    {
        $published_at = ($data->post_time == null && ($data->post_status == 'published' || $data->post_status == 'private')) ? now() : $data->post_time;

        $post = Post::create([
            'status' => $data->post_status,
            'published_at' => $published_at,
            'body' => $data->post_body,
            'type' => $data->post_type,
            'blog_id' => $data->blog_id
        ]);
        return $post;
    }
}
