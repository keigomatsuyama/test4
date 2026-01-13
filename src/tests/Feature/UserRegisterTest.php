<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        $response = $this->post('/register', [
            'username' => 'testuser',
            'email' => 'test@gmail.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);


        // ユーザーがDBにいるか
        $this->assertDatabaseHas('users', [
            'email' => 'test@gmail.com',
        ]);

        // ログイン済みか
        $this->assertAuthenticated();

        // リダイレクト先
        $response->assertRedirect('/mypage/profile');
    }
}
