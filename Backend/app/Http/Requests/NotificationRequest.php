<?php

namespace App\Http\Requests;

use App\Http\Misc\Helpers\Config;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class NotificationRequest extends FormRequest
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
            "type" => [
                "sometimes",
                "string",
                Rule::in(Config::NOTIFICATIONS_TYPES),
            ],
            "for_blog_id" => [
                "sometimes",
                "integer",
                // this rule ensures that this blog belongs to the current loged in user
                Rule::exists('blogs', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::user()->id)
                        ->where('id', request()->for_blog_id);
                })
            ]
        ];
    }
}
