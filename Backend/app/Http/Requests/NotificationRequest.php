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
     * Include the query parameters in the request body data inorder to apply validation on it
     *
     * @return array
     */
    public function all($keys = null)
    {
        $data = parent::all($keys);
        if ($this->route('type')) {
            $data['type'] = $this->route('type');
        }
        if ($this->route('for_blog_id')) {
            $data['for_blog_id'] = $this->route('for_blog_id');
        }

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
            "type" => [
                "sometimes",
                "string",
                Rule::in(Config::NOTIFICATIONS_TYPES),
            ],
            "for_blog_id" => [
                "sometimes",
                "numeric",
                // this rule ensures that this blog belongs to the current loged in user
                Rule::exists('blogs', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::user()->id)
                        ->where('id', request()->for_blog_id);
                })
            ]
        ];
    }
}
