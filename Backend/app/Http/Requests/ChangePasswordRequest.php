<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use App\Http\Misc\Helpers\Errors;

class ChangePasswordRequest extends FormRequest
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
                'current_password' => 'required',
                'password' => ['required','confirmed',Password::min(8)->mixedCase()->numbers()],
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
            'current_password.required' => Errors::MISSING_CHANGE_PASSWORD,
            'password.required' => Errors::MISSING_CHANGE_PASSWORD,
            'password.min' => Errors::PASSWORD_SHORT,
            'password.confirmed' => Errors::INVALID_CHANGE_PASSWORD_CONFORMATION,
        ];
    }
}
