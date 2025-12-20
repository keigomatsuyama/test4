<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'profile_image' => 'nullable|image|mimes:jpeg,png',

            'username' => 'required|string|max:20',

            'zipcode' => [
                'required',
                'regex:/^\d{3}-\d{4}$/',    // 123-4567 の形式
            ],

            'address' => 'required|string',

            'building' => 'required|string|max:255', // 建物必須！！
        ];
    }

    public function messages()
    {
        return [
            'profile_image.image' => 'プロフィール画像は画像ファイルを選択してください。',
            'profile_image.mimes' => 'プロフィール画像はjpegまたはpng形式でアップロードしてください。',

            'username.required' => 'ユーザー名は必須です。',
            'username.max'      => 'ユーザー名は20文字以内で入力してください。',

            'zipcode.required'  => '郵便番号は必須です。',
            'zipcode.regex'     => '郵便番号はハイフンありの8文字（例：123-4567）で入力してください。',

            'address.required'  => '住所は必須です。',

            'building.required' => '建物名は必須です。',
        ];
    }
}
