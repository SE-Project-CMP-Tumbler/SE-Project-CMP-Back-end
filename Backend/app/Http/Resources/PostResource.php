<?php

namespace App\Http\Resources;

use GuzzleHttp\Psr7\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public static $wrap = 'response';
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
        ];
    }

    public function with($request)
    {
        return [
            'meta'  => [
                'status' => 200,
                'msg' => 'OK'
            ]
        ];
    }

    public function withResponse($request, $response)
    {
        $response->setStatusCode(200, 'OK');
    }
}
