<?php

namespace App\Http\Requests;

use App\Http\Misc\Helpers\Config;
use Illuminate\Foundation\Http\FormRequest;

class ImageRequest extends FormRequest
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
        $validTypes = implode(",", Config::VALID_IMAGE_TYPES);
        return [
            // 'image' => 'required|array',
            // 'image.*' => "image|mimes:{$validTypes}|max:" . Config::FILE_UPLOAD_MAX_SIZE
            'image' => "required|mimes:{$validTypes}|max:" . Config::FILE_UPLOAD_MAX_SIZE
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
            'image.required' => 'The :attribute field is required',
            'image.mimes' => 'Not supported :attribute type',
            'image.max' => 'Allowed :attribute max size is ' . Config::FILE_UPLOAD_MAX_SIZE / 1024 . "MB"
        ];
    }
}
