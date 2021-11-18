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
        return [
            'url' => $request->newVideoUrl,
            'width' => $request->width,
            'height' => $request->height,
            'size' => $request->size,
            'duration' => $request->duration,
            'audio_codec' => $request->audio_codec,
            'video_codec' => $request->video_codec,
            'preview_image_url' => $request->preview_image_url,
        ];
    }
}
