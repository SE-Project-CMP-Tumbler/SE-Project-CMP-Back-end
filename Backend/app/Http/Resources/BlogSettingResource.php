<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BlogSettingResource extends JsonResource
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
         'blog_id' => $this->id,
         'blog_username' => $this->username,
         'replies_settings' => $this->replies_settings,
         'ask_settings' => [
            "allow_ask" => $this->allow_ask,
            "ask_page_title" => $this->ask_page_title,
            "allow_anonymous_questions" => $this->allow_anonymous_questions
         ],
         "submissions_settings" => [
            "allow_submittions" => $this->submissions_page_title,
            "submissions_page_title" => $this->submissions_page_title,
            "submissions_guidelines" => $this->submissions_guidelines
         ],
         "allow_messages" => $this->allow_messages

        ];
    }
}
