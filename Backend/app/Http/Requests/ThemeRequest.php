<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ThemeRequest extends FormRequest
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
        $shapes = array('square' ,'circle');
        return [
        "title" => 'array',
        "title.*.text" => 'string|min:1' ,
        "title.*.color" => 'string|min:3',
        "title.*.font" => 'string|min:3|regex:/^[a-zA-Z0-9 ]+$/u',
        "title.*.font_weight" => 'string|min:3|regex:/^[a-zA-Z0-9]+$/u',
        "description" => 'array' ,
        "description.*.text" => 'string|min:1',
        "background_color" => 'string|min:3',
        "accent_color" => 'string|min:3',
        "body_font" => 'string|min:3|regex:/^[a-zA-Z0-9]+$/u',
        "header_image" => 'array',
        "header_image.*.url" => 'string|min:3|url',
        "avatar" => 'array',
        "avatar.*.url" => 'string|min:3|url' ,
        "avatar.*.shape" => ['string',Rule::in($shapes)]
        ];
    }
}
