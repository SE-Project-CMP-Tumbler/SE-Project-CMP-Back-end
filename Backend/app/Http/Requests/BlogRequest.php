<?php

namespace App\Http\Requests;

use App\Http\Misc\Traits\WebServiceResponse;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Misc\Helpers\Errors;

class BlogRequest extends FormRequest
{
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
     * Get the validation rules that apply to blog the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'blog_username' => 'required|min:3|regex:/^[a-zA-Z0-9-]+$/u',
            'title' => 'required|min:3',
            'password' => 'min:3'
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
        'blog_username.regex' => Errors::EMAIL_INVALID_FORMAT,
        ];
    }
}
