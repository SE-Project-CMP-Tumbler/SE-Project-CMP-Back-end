<?php

namespace App\Http\Requests;

use App\Http\Misc\Helpers\Config;
use Illuminate\Foundation\Http\FormRequest;

class VideoRequest extends FormRequest
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
        $validTypes = implode(",", Config::VALID_VIDEO_TYPES);
        return [
            'video' => "required|mimes:{$validTypes}|max:" . Config::FILE_UPLOAD_MAX_SIZE
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
            'video.required' => 'The :attribute field is required',
            'video.mimes' => 'Not supported :attribute type',
            'video.max' => 'Allowed :attribute max size is ' . Config::FILE_UPLOAD_MAX_SIZE / 1024 . "MB"
        ];
    }
}
