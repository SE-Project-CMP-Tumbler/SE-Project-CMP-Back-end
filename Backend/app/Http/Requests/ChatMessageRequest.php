<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ChatMessageRequest extends FormRequest
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
     * Include the query parameters in the request body data inorder to apply validation on it
     *
     * @return array
     */
    public function all($keys = null)
    {
        $data = parent::all($keys);
        $data['chat_room_id'] = $this->route('chat_room_id');

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
            'chat_room_id' => [
                "required",
                "integer",
                'exists:chat_room_gids,id'
             ],
             // to set specificlly which of the logged in user blogs to be the sender else primary is used
            'from_blog_username' => [
                "sometimes",
                "string",
                // this rule ensures that this blog belongs to the current loged in user
                Rule::exists('blogs', 'username')->where(function ($query) {
                    $query->where('user_id', Auth::user()->id)
                        ->where('username', request()->from_blog_username);
                })
            ],
        ];
    }
}
