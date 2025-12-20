@php
use Illuminate\Support\Str;
@endphp
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>マイページ</title>
  <link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
</head>

<body>

  <header class="header">
    <div class="header-left">
      <a href="/">
        <img src="{{ asset('images/logo.png') }}" alt="ロゴ">
      </a>
    </div>

    <div class="header-center">
      <form action="{{ route('top') }}" method="GET">
        <input
          type="text"
          name="keyword"
          class="search-box"
          placeholder="なにをお探しですか？"
          value="{{ request('keyword') }}">
      </form>
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

  <main class="profile">

    <div class="avatar"></div>
    <h2>ユーザー名</h2>

    <button class="edit-btn">
      <a href="/mypage/profile">プロフィールを編集</a>
    </button>
    <!-- ★★★ tabs の中に content を入れた修正版 ★★★ -->
    {{-- タブ --}}
    <div class="tabs">
      <a href="{{ route('mypage', ['page' => 'sell']) }}"
        class="tab-label {{ $page === 'sell' ? 'active' : '' }}">
        出品した商品
      </a>

      <a href="{{ route('mypage', ['page' => 'buy']) }}"
        class="tab-label {{ $page === 'buy' ? 'active' : '' }}">
        購入した商品
      </a>
    </div>

    {{-- コンテンツ --}}
    <div class="content">

      {{-- 出品した商品 --}}
      @if ($page === 'sell')
      <div class="items-wrapper">
        @forelse ($soldItems as $item)
        <div class="item-card">
          @if (Str::startsWith($item->image_path, 'items/'))
          <img src="{{ asset('storage/' . $item->image_path) }}" alt="商品画像">
          @else
          <img src="{{ asset('images/' . $item->image_path) }}" alt="商品画像">
          @endif
          <p class="item-name">{{ $item->name }}</p>
        </div>
        @empty
        <p class="empty-message">出品した商品はありません。</p>
        @endforelse
      </div>
      @endif

      {{-- 購入した商品 --}}
      @if ($page === 'buy')
      <div class="items-wrapper">
        @forelse ($boughtItems as $item)
        <div class="item-card">
          <img src="{{ asset('images/' . $item->image_path) }}" alt="商品画像">
          <p class="item-name">{{ $item->name }}</p>
          <p class="item-price">¥{{ number_format($item->price) }}</p>
        </div>
        @empty
        <p class="empty-message">購入した商品はありません。</p>
        @endforelse
      </div>
      @endif

    </div>



    </div>
    </div>

    </div><!-- content end -->

    </div><!-- tabs end -->

  </main>

</body>

</html>