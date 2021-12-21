<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use App\Http\Misc\Helpers\Errors;

class UserCheckRegisterCredentials extends FormRequest
{
/**
 * Indicates if the validator should stop on the first rule failure.
 *
 * @var bool
 */
    protected $stopOnFirstFailure = true;
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
                'email' => 'required_without_all:blog_username,password,age|required|email|unique:users',
                'blog_username' => 'required_with_all:email,password',
                'password' => ['required_with:email',Password::min(8)->mixedCase()->numbers()],
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
        'email.email' => Errors::NOT_VALID_EMAIL,
        'email.unique' => Errors::EMAIL_TAKEN,
        'password.required_with' => Errors::MISSING_PASSWORD,
        'password.min' => Errors::PASSWORD_SHORT,
        'blog_username.required_with_all' => Errors::MISSING_BLOGNAME,
        ];
    }
}
