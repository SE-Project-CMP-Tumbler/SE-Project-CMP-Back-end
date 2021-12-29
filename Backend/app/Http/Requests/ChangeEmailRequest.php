<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Misc\Helpers\Errors;

class ChangeEmailRequest extends FormRequest
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
                'email' => 'required_without:password|required|email|unique:users',
                'password' => 'required_with:email'
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
        'email.required_without' => Errors::MISSING_BOTH_EMAIL_PASSWORD,
        'email.required' => Errors::MISSING_EMAIL,
        'email.unique' => Errors::EMAIL_TAKEN,
        'password.required_with' => Errors::MISSING_PASSWORD,
        ];
    }
}
