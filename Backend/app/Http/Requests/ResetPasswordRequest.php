<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use App\Http\Misc\Helpers\Errors;

class ResetPasswordRequest extends FormRequest
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
            'token' => 'required',
            'email' => 'required|email',
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
        'email.required' => Errors::MISSING_EMAIL,
        'email.email' => Errors::NOT_VALID_EMAIL,
        'password.required' => Errors::MISSING_PASSWORD,
        'password.min' => Errors::PASSWORD_SHORT,
        'password.confirmed' => Errors::MISSING_PASSWORD_CONFORMATION,
        ];
    }
}
