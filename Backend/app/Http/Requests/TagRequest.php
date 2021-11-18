<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TagRequest extends FormRequest
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
     * Include the query parameter tag_description and post_id in the request data inorder to apply validation on them
     *
     * @return array
     */
    public function all($keys = null)
    {
        $data = parent::all($keys);
        $data['post_id'] = $this->route('post_id');
        $data['tag_description'] = $this->route('tag_description');

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
            'tag_description' => ['required', 'string'],
            'post_id' => ['required','numeric']
        ];
    }
}
