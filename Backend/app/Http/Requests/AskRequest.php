<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Misc\Helpers\Errors;

class AskRequest extends FormRequest
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

    public function all($keys = null)
    {
        $data = parent::all($keys);
        $data['blog_id'] = $this->route('blog_id');

        return $data;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
                'blog_id' => 'required|numeric|exists:blogs,id', //must be a blog id that exists in database
                'question_body' => 'required|string',
                'question_flag' => 'required|boolean',
        ];
    }
}
