<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ログイン</title>
  <link rel="stylesheet" href="/css/login.css" />
</head>

<body>
  <header class="header">
    <img src="{{ asset('images/logo.png') }}" alt="ロゴ">

  </header>
  <main class="login-container">
    <h1 class="login-title">ログイン</h1>
    <form class="login-form" action="/login" method="POST">
      @csrf
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
      <button type="submit">ログインする</button>
    </form>

    <p class="register-link"><a href="/register">会員登録はこちら</a></p>
  </main>
</body>

</html>