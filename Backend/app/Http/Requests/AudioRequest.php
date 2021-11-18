<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AudioRequest extends FormRequest
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
            'audio' => 'required|mimes:mp3,wav,3gp,3gpp|max:102400'
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
            'audio.required' => 'The :attribute field is required',
            'audio.mimes' => 'Not supported :attribute type',
            'audio.max' => 'Allowed :attribute max size is 100MB'
        ];
    }
}
