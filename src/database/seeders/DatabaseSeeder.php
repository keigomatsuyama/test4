<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
   public function run()
    {
        $this->call([
            UserSeeder::class,               // ★ 0. Seeder専用ユーザー
            ConditionSeeder::class,          // ① condition
            CategorySeeder::class,           // ② category
            ExhibitionSeeder::class,         // ③ exhibition（user_id=999）
            CategoryExhibitionSeeder::class, // ④ 中間テーブル
        ]);
    }
}