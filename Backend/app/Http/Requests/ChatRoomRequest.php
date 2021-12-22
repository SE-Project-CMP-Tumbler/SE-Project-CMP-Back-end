<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ChatRoomRequest extends FormRequest
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
        return [
            'from_blog_username' => [
                "required",
                "string",
                // this rule ensures that this blog belongs to the current loged in user
                Rule::exists('blogs', 'username')->where(function ($query) {
                    $query->where('user_id', Auth::user()->id)
                        ->where('username', request()->from_blog_username);
                })
            ],
            'to_blog_username' => [
                "required",
                "string",
                "different:from_blog_username",
                "exists:blogs,username",
            ],
        ];
    }
}
