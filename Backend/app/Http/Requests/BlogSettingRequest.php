<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class BlogSettingRequest extends FormRequest
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
     * Get the validation rules that apply  to blog settings the request.
     *
     * @return array
     */
    public function rules()
    {
        $replies = array('Everyone can reply','Tumblrs you follow and Tumblrs following you for a week can reply','Only Tumblrs you follow can reply');
        return [
            'allow_messages' => 'boolean',
            'replies_settings' => ['string',Rule::in($replies)],
            'allow_submittions' => 'boolean',
            'submissions_page_title' => 'string|nullable',
            'submissions_guidelines' => 'string|nullable',
            'allow_ask' => 'boolean',
            'allow_anonymous_questions' => 'boolean',
            'ask_page_title' => 'string|nullable',
            'share_likes' => 'boolean',
            'share_followings' => 'boolean'
        ];
    }
}
