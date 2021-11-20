<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExtImageRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'imageUrl' => 'required|url'
        ];
    }

    /**
     * Get the correct error message for each validation
     *
     * @return array
     **/
    public function messages()
    {
        return [
            'imageUrl.required' => 'The :attribute field is required',
            'imageUrl.url' => ':attribute should be valid image url',
        ];
    }
}
