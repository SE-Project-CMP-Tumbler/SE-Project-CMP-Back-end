<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use App\Http\Misc\Helpers\Errors;

class UserRegisterRequest extends FormRequest
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
                'email' => 'required_without_all:blog_username,password,age|required',
                'blog_username' => 'required_with_all:email,password',
                'password' => 'required_with:email',
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
        'email.required_without_all' => Errors::MISSING_BOTH_EMAIL_PASSWORD,
        'email.required' => Errors::MISSING_EMAIL,
        'password.required_with' => Errors::MISSING_PASSWORD,
        'blog_username.required_with_all' => Errors::MISSING_BLOGNAME,
        ];
    }
}
