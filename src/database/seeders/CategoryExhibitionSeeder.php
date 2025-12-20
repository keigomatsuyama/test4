<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryExhibitionSeeder extends Seeder
{
    public function run()
    {
        $mapping = [
            1 => [1, 5],
            2 => [2],
            3 => [14],
            4 => [1, 5],
            5 => [2],
            6 => [2],
            7 => [1],
            8 => [10],
            9 => [10],
            10 => [6],
        ];

        foreach ($mapping as $exhibition_id => $categories) {
            foreach ($categories as $category_id) {
                DB::table('category_exhibition')->insert([
                    'exhibition_id' => $exhibition_id,
                    'category_id'   => $category_id,
                ]);
            }
        }
    }
}
