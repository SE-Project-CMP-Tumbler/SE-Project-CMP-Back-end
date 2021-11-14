<?php

namespace App\Http\Requests;

use App\Models\Blog;
use Illuminate\Contracts\Session\Session;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // $id = $this->route('blog_id');
        // return $id == Session::get('blog_id');
        return true;
    }

    //include the query parameter blog_id in the request data inorder to apply validation on it
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
        $types = array('answer', 'general', 'text', 'audio', 'video', 'chat', 'link', 'image', 'quote');
        $statuses = array('published', 'draft', 'private', 'submission');
        return [
            'post_body' => ['required'],
            'post_type' => ['required', Rule::in($types)], //should i calculate it or the front?
            'post_time' => ['nullable', 'date'],
            'post_status' => ['required', Rule::in($statuses)], //we should specify these specific values to the front
            'blog_id' => 'required|exists:blogs,id' //must be a blog id of the blogs of the auth user
        ];
    }
}
