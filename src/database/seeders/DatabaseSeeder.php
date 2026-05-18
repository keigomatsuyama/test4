<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            ConditionSeeder::class,
            CategorySeeder::class,
            DemoSeeder::class,
            CategoryExhibitionSeeder::class,
        ]);
    }
}