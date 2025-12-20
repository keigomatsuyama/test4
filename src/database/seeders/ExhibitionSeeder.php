<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExhibitionSeeder extends Seeder
{
    public function run()
    {
        DB::table('exhibitions')->insert([
            [
                'user_id' => 2,
                'name' => '腕時計',
                'image_path' => 'watch.jpg',
                'item_description' => 'スタイリッシュなデザインのメンズ腕時計',
                'condition_id' => 1,
                'price' => 15000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'name' => 'HDD',
                'image_path' => 'hdd.jpg',
                'item_description' => '高速で信頼性の高いハードディスク',
                'condition_id' => 2,
                'price' => 5000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'name' => '玉ねぎ3束',
                'image_path' => 'onion.jpg',
                'item_description' => '新鮮な玉ねぎ3束のセット',
                'condition_id' => 3,
                'price' => 300,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'name' => '革靴',
                'image_path' => 'shoes.jpg',
                'item_description' => 'クラシックなデザインの革靴',
                'condition_id' => 4,
                'price' => 4000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'name' => 'ノートPC',
                'image_path' => 'laptop.jpg',
                'item_description' => '高性能なノートパソコン',
                'condition_id' => 1,
                'price' => 45000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'name' => 'マイク',
                'image_path' => 'mic.jpg',
                'item_description' => '高音質のレコーディング用マイク',
                'condition_id' => 2,
                'price' => 8000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'name' => 'ショルダーバッグ',
                'image_path' => 'bag.jpg',
                'item_description' => 'おしゃれなショルダーバッグ',
                'condition_id' => 3,
                'price' => 3500,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'name' => 'タンブラー',
                'image_path' => 'tumbler.jpg',
                'item_description' => '使いやすいタンブラー',
                'condition_id' => 4,
                'price' => 500,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'name' => 'コーヒーミル',
                'image_path' => 'coffeemill.jpg',
                'item_description' => '手動のコーヒーミル',
                'condition_id' => 1,
                'price' => 4000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'name' => 'メイクセット',
                'image_path' => 'makeup.jpg',
                'item_description' => '便利なメイクアップセット',
                'condition_id' => 2,
                'price' => 2500,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
