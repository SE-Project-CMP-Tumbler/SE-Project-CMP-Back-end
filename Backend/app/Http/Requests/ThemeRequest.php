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
        "title" => 'string|min:1' ,
        "color_title" => 'string|min:3',
        "font_title" => 'string|min:3|regex:/^[a-zA-Z0-9 ]+$/u',
        "font_weight_title" => 'string|min:3|regex:/^[a-zA-Z0-9 ]+$/u',
        "description" => 'string|min:1' ,
        "background_color" => 'string|min:3',
        "accent_color" => 'string|min:3',
        "body_font" => 'string|min:3|regex:/^[a-zA-Z0-9 ]+$/u',
        "header_image" => 'string|min:3|url',
        "avatar" => 'string|min:3|url',
        "avatar_shape" => ['string',Rule::in($shapes)]
        ];
    }
}
