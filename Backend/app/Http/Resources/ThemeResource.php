<?php

namespace App\Http\Resources;

use App\Models\Blog;
use Illuminate\Http\Resources\Json\JsonResource;

class ThemeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $blog = $this->blog;
        return
        [

            "color_title" => $this->color_title ,
            "font_title" => $this->font_title,
            "font_weight_title" => $this->font_weight_title,
            "description" => $blog->description,
            "title" => $blog->title ,
            "background_color" => $this->background_color,
            "accent_color" => $this->accent_color,
            "body_font" => $this->body_font,
            "header_image" => $blog->header_image,
            "avatar" => $blog->avatar,
            "avatar_shape" => $blog->avatar_shape

        ];
    }
}
