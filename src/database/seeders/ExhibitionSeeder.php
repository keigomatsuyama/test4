<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exhibition;
use Illuminate\Support\Facades\DB;

class ExhibitionSeeder extends Seeder
{
    public function run()
{
    // ★ Seeder専用ユーザーID（存在するIDにする）
    $seedUserId = 999;

    $items = [
        ['腕時計', 'watch.jpg', 'スタイリッシュなデザインのメンズ腕時計', 1, 15000],
        ['HDD', 'hdd.jpg', '高速で信頼性の高いハードディスク', 2, 5000],
        ['玉ねぎ3束', 'onion.jpg', '新鮮な玉ねぎ3束のセット', 3, 300],
        ['革靴', 'shoes.jpg', 'クラシックなデザインの革靴', 4, 4000],
        ['ノートPC', 'laptop.jpg', '高性能なノートパソコン', 1, 45000],
        ['マイク', 'mic.jpg', '高音質のレコーディング用マイク', 2, 8000],
        ['ショルダーバッグ', 'bag.jpg', 'おしゃれなショルダーバッグ', 3, 3500],
        ['タンブラー', 'tumbler.jpg', '使いやすいタンブラー', 4, 500],
        ['コーヒーミル', 'coffeemill.jpg', '手動のコーヒーミル', 1, 4000],
        ['メイクセット', 'makeup.jpg', '便利なメイクアップセット', 2, 2500],
    ];

    foreach ($items as $item) {
        DB::table('exhibitions')->insert([
            'user_id'          => $seedUserId, // ★ ここだけ
            'name'             => $item[0],
            'image_path'       => $item[1],
            'item_description' => $item[2],
            'condition_id'     => $item[3],
            'price'            => $item[4],
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);
    }
}
}
