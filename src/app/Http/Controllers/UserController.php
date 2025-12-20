<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileRequest;
use Symfony\Component\HttpKernel\Profiler\Profiler;

class UserController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        Auth::login($user);

        return redirect('/mypage/profile');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect('/');
        }

        return back()->withErrors([
            'password' => 'メールアドレスまたはパスワードが正しくありません。',
        ]);
    }

    public function profile()
    {
        return view('auth.profile');
    }
    public function updateProfile(ProfileRequest $request)
    {
        $user = Auth::user();

        // プロフィールが無ければ作成（必須項目も渡す）
        $profile = $user->profile()->firstOrCreate(
            ['user_id' => $user->id],
            [
                'username'    => $request->username,
                'postal_code' => $request->zipcode,
                'address'     => $request->address,
                'building'    => $request->building,
                'profile_image' => null,
            ]
        );

        // 画像アップロード
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $profile->profile_image = $path;
        }

        // その他の項目
        $profile->username    = $request->username;
        $profile->postal_code = $request->zipcode;
        $profile->address     = $request->address;
        $profile->building    = $request->building;

        $profile->save();

        return redirect('/')->with('success', 'プロフィールを更新しました！');
    }
}
