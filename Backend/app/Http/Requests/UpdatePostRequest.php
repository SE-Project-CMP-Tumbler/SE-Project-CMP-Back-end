<?php

namespace App\Http\Requests;

use App\Http\Misc\Helpers\Config;
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
        return [
            'post_body' => ['sometimes', 'required'],
            'post_type' => [Rule::in(Config::POST_TYPES)],
            'post_time' => ['nullable', 'date'],
            'post_status' => [Rule::in(Config::POST_STATUS_TYPES)],
            'pinned' => ['sometimes', 'boolean']
        ];
    }
}
