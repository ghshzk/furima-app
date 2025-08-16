<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
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
            'rating' => ['required', 'integer', 'min:1', 'max:5']
        ];
    }

    public function messages()
    {
        return [
            'rating.required' => '評価を入力してください',
            'rating.integer' => '評価は1~5で入力してください',
            'rating.min' => '評価は最低1つ以上選択してください',
            'rating.max' => '評価は1~5で入力してください'
        ];
    }

    protected $errorBag = 'reviewErrors';
}
