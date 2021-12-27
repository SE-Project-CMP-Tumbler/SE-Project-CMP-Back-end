<?php

namespace App\Http\Requests;

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
    return [
        "theme-id" => 123456789,
        "title" => [
            [
            "text" =>  'string',
            "color" => "#000000",
            "font" => "Gibson",
            "font_weight" => "bold"
            ]
        ],
        "description" => [
            [
            "text" => "Just for cpp nurds"
            ]
        ],
        "background_color" => "#FFFFFF",
        "accent_color" => "#e17e66",
        "body_font" => "Helvetica Neue",
        "header_image" => [
            [
            "url" => "assksineuug"
            ]
        ],
        "avater" => [
           [
            "url" => "aksmdnurjrj",
            "shape" => "circle"
           ]
        ]
    ];
}
