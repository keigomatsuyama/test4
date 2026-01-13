<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // ★ Seeder専用ユーザー（ログインしない）
        DB::table('users')->insert([
            'id' => 999, // ← ExhibitionSeeder と合わせる
              'username' => 'seeder_user', // ★ 必須
            'email' => 'seed@example.com',
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
