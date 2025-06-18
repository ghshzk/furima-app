<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
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
            //'name' => ['required'], 住所変更画面に氏名入力欄はないので削除
            'postcode' => ['required','regex:/^\d{3}-\d{4}$/'],
            'address' => ['required'],
            //'building' => ['required'], 建物名が必ずしもあるとは限らないので削除
        ];
    }

    public function messages()
    {
        return [
            //'name.required' => 'お名前を入力してください',
            'postcode.required' => '郵便番号を入力してください',
            'postcode.regex' => '郵便番号はハイフンありの8文字で入力してください',
            'address.required' => '住所を入力してください',
            //'building.required' => '建物名を入力してください',
        ];
    }
}
