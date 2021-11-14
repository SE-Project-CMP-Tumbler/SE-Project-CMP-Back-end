<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $types = array('answer', 'general', 'text', 'audio', 'video', 'chat', 'link', 'image', 'quote');
        $statuses = array('published', 'draft', 'private', 'submission');
        return [
            'post_type' => [Rule::in($types)],
            'post_time' => ['nullable', 'date'],
            'post_status' => [Rule::in($statuses)],
            'pinned' => ['sometimes', 'boolean']
        ];
    }
}
