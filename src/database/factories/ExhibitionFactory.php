<?php

namespace Database\Factories;

use App\Models\Exhibition;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExhibitionFactory extends Factory
{
    protected $model = Exhibition::class;

    public function definition()
    {
        return [
            // ★ ログインユーザーと被らないID
            'user_id' => 999,

            // condition テーブルがある前提（1〜3など）
            'condition_id' => 1,

            'name' => $this->faker->word(),

            // public/images に置いてある画像名
            'image_path' => 'sample.jpg',

            'item_description' => $this->faker->sentence(),

            'price' => $this->faker->numberBetween(1000, 10000),
        ];
    }
}
