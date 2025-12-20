<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>会員登録</title>
  <link rel="stylesheet" href="/css/register.css" />
</head>
<body>
  <header class="header">
    <img src="{{ asset('images/logo.png') }}" alt="ロゴ">
  </header>

  <main class="login-container">
    <h1 class="login-title">会員登録</h1>
    <form class="login-form"
    action="/register" method="POST">
      @csrf
      <label for="username">ユーザー名</label>
      <input type="text" id="username" name="username"  />
      @error('username')
    <div class="error-message">{{ $message }}</div>
@enderror
      <label for="email">メールアドレス</label>
      <input type="text" id="email" name="email" />
@error('email')
    <div class="error-message">{{ $message }}</div>
@enderror
      <label for="password">パスワード</label>
      <input type="text" id="password" name="password" />
@error('password')
    <div class="error-message">{{ $message }}</div>
@enderror
      <label for="password-confirmation">確認用パスワード</label>
      <input type="text" id="password-confirmation" name="password_confirmation"  />
      <button type="submit">登録する</button>
    </form>

    <p class="register-link"><a href="/login">ログインはこちら</a></p>
  </main>
</body>
</html>
