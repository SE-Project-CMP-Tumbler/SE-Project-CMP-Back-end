<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Http\Misc\Helpers\Config;

class BlogPostRequest extends FormRequest
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
            'blog_id' => [
                "required",
                "numeric",
                // this rule ensures that this blog belongs to the current loged in user
                Rule::exists('blogs', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::user()->id)
                        ->where('id', request()->blog_id);
                })
            ],
            'post_id' => [
                "required",
                "numeric",
                // this rule ensures that this post belongs to that belog with blog_id
                Rule::exists('posts', 'id')->where(function ($query) {
                    $query->where('blog_id', request()->blog_id)
                        ->where('id', request()->post_id);
                })
            ],
            'post_status' => [
                'sometimes',
                'string',
                Rule::in(Config::POST_STATUS_TYPES),
            ]
        ];
    }
}
