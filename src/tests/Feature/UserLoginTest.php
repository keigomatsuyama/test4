<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login()
    {
        // 先にユーザーを作る（登録済み状態）
        $user = User::factory()->create([
            'email' => 'test@gmail.com',
            'password' => bcrypt('password123'),
        ]);

        // ログイン処理
        $response = $this->post('/login', [
            'email' => 'test@gmail.com',
            'password' => 'password123',
        ]);

        // ログインできているか
        $this->assertAuthenticatedAs($user);

        // リダイレクト先
        $response->assertRedirect('/');
    }
}
