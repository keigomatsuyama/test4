<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>プロフィール設定</title>
  <link rel="stylesheet" href="/css/register2.css" />
</head>

<body>

  <body>
    <header class="header">
      <div class="header-left">
        <a href="/">
        <img src="{{ asset('images/logo.png') }}" alt="ロゴ">
        </a>
      </div>

      <div class="header-center">
        <input type="text" class="search-box" placeholder="なにをお探しですか？">
      </div>
      <div class="header-right">
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="header-link" style="background: none; border: none; cursor: pointer;">
            ログアウト
          </button>
        </form>
        <a href="/mypage" class="header-link">マイページ</a>
        <a href="/sell" class="sell-btn">出品</a>
      </div>
    </header>

    <main class="profile-container">
      <h1 class="profile-title">プロフィール設定</h1>

      <div class="profile-image">
        <div class="image-placeholder">   @if(Auth::user()->profile && Auth::user()->profile->image_path)
      <img
        src="{{ asset('storage/' . Auth::user()->profile->image_path) }}"
        alt="プロフィール画像">
    @endif</div>
        <label class="image-upload-btn">
          画像を選択する
          <input type="file" name="profile_image" form="profileForm" hidden>
        </label>
        @error('profile_image') <div class="error-message">{{ $message }}</div> @enderror
      </div>

      <form id="profileForm" action="/mypage/profile" method="POST" enctype="multipart/form-data" class="profile-form">
        @csrf

        <label for="username">ユーザー名</label>
        <input type="text" id="username" name="username" value="{{ old('username', Auth::user()->profile->username ?? '') }}">
        @error('username') <div class="error-message">{{ $message }}</div> @enderror

        <label for="zipcode">郵便番号</label>
        <input type="text" id="zipcode" name="zipcode" value="{{ old('zipcode', Auth::user()->profile->postal_code ?? '') }}">
        @error('zipcode') <div class="error-message">{{ $message }}</div> @enderror

        <label for="address">住所</label>
        <input type="text" id="address" name="address" value="{{ old('address', Auth::user()->profile->address ?? '') }}">
        @error('address') <div class="error-message">{{ $message }}</div> @enderror

        <label for="building">建物名</label>
        <input type="text" id="building" name="building" value="{{ old('building', Auth::user()->profile->building ?? '') }}">
        @error('building') <div class="error-message">{{ $message }}</div> @enderror

        <button type="submit">更新する</button>
      </form>
    </main>
  </body>

</html>