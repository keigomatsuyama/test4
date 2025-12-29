<!-- purchase.blade.php -->
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>購入手続き</title>
  <link rel="stylesheet" href="/css/purchase.css">
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

    <main class="purchase-container">

      <!-- 左カラム -->
      <div class="left">

        <div class="item-info">
          <img src="{{ asset('images/' . $item->image_path) }}"
            alt="{{ $item->name }}"
            class="product-image">

          <div class="item-text">
            <h1>{{ $item->name }}</h1>
            <p class="price">¥{{ number_format($item->price) }}</p>
          </div>
        </div>

        <div class="section">
          <h2>支払い方法</h2>

          <form method="GET" action="{{ route('purchase.show', ['item_id' => $item->id]) }}">
            @csrf
            <select name="payment_method" onchange="this.form.submit()">
              <option value="">選択してください</option>
              <option value="コンビニ払い" {{ $selected === 'コンビニ払い' ? 'selected' : '' }}>
                コンビニ払い
              </option>
              <option value="クレジットカード" {{ $selected === 'クレジットカード' ? 'selected' : '' }}>
                クレジットカード
              </option>
            </select>
          </form>
        </div>

        <div class="section address">
          <div class="address-header">
            <h2>配送先</h2>
            <a href="{{ route('purchase.address.edit', ['item_id' => $item->id]) }}">変更する</a>
          </div>

          <p>
            〒 {{ $profile->postal_code }}<br>
            住所 {{ $profile->address }}<br>
            建物名 {{ $profile->building }}
          </p>
        </div>


      </div>

      <!-- 右カラム -->
      <div class="right">
        <div class="summary-box">
          <div class="summary-row">
            <span class="label">商品代金</span>
            <span class="price">¥{{ number_format($item->price) }}</span>
          </div>
          <div class="summary-row">
            <span class="label">支払い方法</span>
            <span class="value">{{ $selected }}</span>
          </div>
        </div>


        <form method="POST" action="{{ route('purchase.store') }}">
          @csrf

          <input type="hidden" name="item_id" value="{{ $item->id }}">
          <input type="hidden" name="total_price" value="{{ $item->price }}">
          <input type="hidden" name="payment_method" value="{{ $selected ?? '' }}">

          <input type="hidden" name="shipping_name" value="{{ $profile->name ?? Auth::user()->name ?? '未設定' }}">
          <input type="hidden" name="shipping_postal" value="{{ $profile->postal_code ?? '000-0000' }}">
          <input type="hidden" name="shipping_address" value="{{ $profile->address ?? '未設定住所' }}">
          <input type="hidden" name="shipping_building" value="{{ $profile->building ?? '' }}">
          <input type="hidden" name="shipping_phone" value="{{ $profile->phone ?? '0000000000' }}">

          <button type="submit" class="purchase-btn">購入する</button>
        </form>

      </div>

    </main>


  </body>

</html>