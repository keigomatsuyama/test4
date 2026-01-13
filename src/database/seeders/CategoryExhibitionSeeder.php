<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryExhibitionSeeder extends Seeder
{
    public function run()
    {
        $mapping = [
            '腕時計'       => [1, 5],
            'HDD'          => [2],
            '玉ねぎ3束'   => [14],
            '革靴'         => [1, 5],
            'ノートPC'     => [2],
            'マイク'       => [2],
            'ショルダーバッグ' => [1],
            'タンブラー'   => [10],
            'コーヒーミル' => [10],
            'メイクセット' => [6],
        ];

        foreach ($mapping as $itemName => $categoryIds) {
            $exhibition = DB::table('exhibitions')
                ->where('name', $itemName)
                ->first();

            if (!$exhibition) {
                continue; // 存在しなければスキップ
            }

            foreach ($categoryIds as $categoryId) {
                DB::table('category_exhibition')->insert([
                    'exhibition_id' => $exhibition->id,
                    'category_id'   => $categoryId,
                ]);
            }
        }
    }
}
