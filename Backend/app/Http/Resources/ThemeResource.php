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
            "theme-id" => $this->id,
            "title" => [
                [
                  "text" => $blog->title,
                  "color" => $this->color_title,
                  "font" => $this->font_title,
                  "font_weight" => $this->font_weight_title
                ]
              ],
              "description" => [
                [
                  "text" => $blog->description
                ]
              ],
              "background_color" => $this->background_color,
              "accent_color" => $this->accent_color,
              "body_font" => $this->body_font,
              "header_image"  => [
                [
                  "url" => $blog->header_image
                ]
              ],
              "avater" => [
                [
                  "url" => $blog->avatar,
                  "shape" =>  $blog->avatar_shape
                ]
              ]

        ];
    }
}
