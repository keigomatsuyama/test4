<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            // 商品名
            'name' => ['required', 'string', 'max:255'],

            // 商品説明
            'description' => ['required', 'string', 'max:255'],

            // 商品画像（jpg / png）
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png'],

            // カテゴリー（チェックボックス）
            'category_id' => ['required', 'array'],
            'category_id.*' => ['integer'],

            // 商品の状態
            'condition_id' => ['required', 'integer'],

            // 商品価格
            'price' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '商品名は必須です',

            'description.required' => '商品説明は必須です',
            'description.max' => '商品説明は255文字以内で入力してください',

            'image.required' => '商品画像は必須です',
            'image.image' => '画像ファイルを選択してください',
            'image.mimes' => '画像はjpgまたはpng形式でアップロードしてください',

            'category_id.required' => 'カテゴリーを選択してください',

            'condition_id.required' => '商品の状態を選択してください',

            'price.required' => '価格は必須です',
            'price.numeric' => '価格は数値で入力してください',
            'price.min' => '価格は0円以上で入力してください',
        ];
    }
}
