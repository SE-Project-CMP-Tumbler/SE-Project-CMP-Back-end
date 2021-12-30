<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AnswerRequest extends FormRequest
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
        $data['question_id'] = $this->route('question_id');

        return $data;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $types = array('answer');
        $statuses = array('published', 'draft', 'private', 'submission');
        return [
            'question_id' => 'required|numeric|exists:questions,id', //must be a questions id that exists in database
            'post_body' => ['required'],
            'post_type' => ['required', Rule::in($types)],
            'post_time' => ['nullable', 'date'],
            'post_status' => ['required', Rule::in($statuses)], //we should specify these specific values to the front
        ];
    }
}
