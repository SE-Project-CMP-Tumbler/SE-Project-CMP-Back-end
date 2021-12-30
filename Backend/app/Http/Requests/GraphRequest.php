<?php

namespace App\Http\Requests;

use App\Http\Misc\Helpers\Config;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class GraphRequest extends FormRequest
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
        $data['blog_id'] = $this->route('blog_id');
        $data['period'] = $this->route('period');
        $data['rate'] = $this->route('rate');
        return $data;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // last: day, 3 days, week, month
        $allowedPeriods = [1, 3, 7, 30];

        // hourly, daily
        $allowedRates = [0, 1];

        return [
            "blog_id" => [
                "required",
                "integer",
                // this rule ensures that this blog belongs to the current loged in user
                Rule::exists('blogs', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::user()->id)
                        ->where('id', request()->blog_id);
                })
            ],
            "period" => [
                "required",
                "integer",
                Rule::in($allowedPeriods),
            ],
            "rate" => [
                "required",
                "integer",
                Rule::in($allowedRates),
            ]
        ];
    }
}
