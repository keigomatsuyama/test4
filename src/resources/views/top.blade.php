<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>商品一覧</title>
  <link rel="stylesheet" href="/css/top.css">
</head>

<body>
  <header class="header">
    <div class="header-left">
      <img src="{{ asset('images/logo.png') }}" alt="ロゴ">
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
  <div class="tab-area">
    <a class="tab {{ $tab === 'recommend' ? 'active' : '' }}"
      href="{{ route('top', [
        'tab' => 'recommend',
        'keyword' => request('keyword')
    ]) }}">
      おすすめ
    </a>
    <a class="tab {{ $tab === 'mylist' ? 'active' : '' }}"
      href="{{ route('top', [
        'tab' => 'mylist',
        'keyword' => request('keyword')
    ]) }}">
      マイリスト
    </a>
  </div>
  <div class="products-area">
    @foreach ($exhibitions as $exhibition)
    <div class="product-card">
      @php
      $isBought = in_array($exhibition->id, $boughtItemIds);
      @endphp

      @if (!$isBought)
      <a href="{{ route('item.show', ['id' => $exhibition->id]) }}">
        @endif

        <div class="product-image">
          <img src="{{ asset('images/' . $exhibition->image_path) }}" alt="{{ $exhibition->name }}" width="150">
        </div>
        <p class="product-name">
          {{ $exhibition->name }}
          @if ($isBought)
          <span style="color: red;">（SOLD）</span>
          @endif
        </p>

        @if (!$isBought)
      </a>
      @endif
    </div>
    @endforeach
  </div>
</body>

</html>