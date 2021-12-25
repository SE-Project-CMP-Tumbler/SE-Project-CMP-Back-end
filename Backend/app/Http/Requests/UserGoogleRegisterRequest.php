<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Misc\Helpers\Errors;

class UserGoogleRegisterRequest extends FormRequest
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
            'google_access_token' => 'required',
            'blog_username' => 'required|regex:/^[a-zA-Z0-9-]+$/u',
            'age' => 'required|integer|min:13|max:130',
        ];
    }
/**
 * Get the error messages for the defined validation rules.
 *
 * @return array
 */
    public function messages()
    {
        return [
        'blog_username.required' => Errors::MISSING_BLOGNAME,
        'blog_username.regex' => Errors::EMAIL_INVALID_FORMAT,
        'age.required' => Errors::MISSING_AGE,
        'age.integer' => Errors::INVALID_AGE,
        'age.min' => Errors::MIN_AGE,
        'age.max' => Errors::INVALID_AGE,
        ];
    }
}
