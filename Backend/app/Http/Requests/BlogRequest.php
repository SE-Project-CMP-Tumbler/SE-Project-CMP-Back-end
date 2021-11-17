<?php

namespace App\Http\Requests;

use App\Http\Misc\Traits\WebServiceResponse;
use Illuminate\Foundation\Http\FormRequest;

class BlogRequest extends FormRequest
{
    //use WebServiceResponse;

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
            'blog_username' => 'required|min:3',
            'title' => 'required|min:3',
            'password' => 'min:3'
        ];
    }
}
