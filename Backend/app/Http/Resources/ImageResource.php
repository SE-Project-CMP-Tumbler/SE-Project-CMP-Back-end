<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
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
            'url' => $this->url,
            'width' => $this->width,
            'height' => $this->height,
            'orignal_filename' => $this->orignal_filename,
            'routation' => $this->routation,
            'upload_id' => $this->upload_id,
        ];
    }
}
