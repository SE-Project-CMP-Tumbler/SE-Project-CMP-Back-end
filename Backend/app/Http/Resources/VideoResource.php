<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if(is_null($this->body)) {
            return [
                'url' => $this->url,
                'width' => $this->width,
                'height' => $this->height,
                'size' => $this->size,
                'duration' => $this->duration,
                'audio_codec' => $this->audio_codec,
                'video_codec' => $this->video_codec,
                'preview_image_url' => $this->preview_image_url,
            ];
        } else {
            return [
                'body' => $this->body,
                'width' => $this->width,
                'height' => $this->height,
            ];
        }
    }
}
