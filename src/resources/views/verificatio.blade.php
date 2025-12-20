<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メール認証</title>
    <link rel="stylesheet" href="/css/verify.css">
</head>
<body>

<header class="header">
    <img src="/images/logo.png" class="logo">
</header>

<div class="container">
    <p class="message">
        登録していただいたメールアドレスに認証メールを送付しました。<br>
        メール認証を完了してください。
    </p>

    <a href="https://mail.google.com/" target="_blank" class="btn">
        認証はこちらから
    </a>

    <form method="POST" action="{{ route('verification.resend') }}">
        @csrf
        <button class="resend-btn">認証メールを再送する</button>
    </form>
</div>

</body>
</html>
