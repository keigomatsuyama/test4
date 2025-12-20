<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>商品の出品</title>
  <link rel="stylesheet" href="{{ asset('css/sell.css') }}" />
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

  <main class="form-container">
    <h1>商品の出品</h1>
    <form class="item-form" action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <label>商品画像</label>
      <div class="image-upload">
        <label class="image-select" for="image">画像を選択する</label>
        <input id="image" type="file" name="image" class="file-input">
      </div>


      <h2>商品の詳細</h2>

      <label>カテゴリー</label>
      <div class="category-container">
        @foreach ($categories as $category)
        <input type="checkbox"
          name="category_id[]"
          id="cat{{ $category->id }}"
          value="{{ $category->id }}"
          class="category-checkbox">
        <label for="cat{{ $category->id }}" class="category-label">{{ $category->name }}</label>
        @endforeach
      </div>

      <label>商品の状態</label>
      <select name="condition_id">
        @foreach ($conditions as $condition)
        <option value="{{ $condition->id }}">{{ $condition->condition }}</option>
        @endforeach
      </select>

      <label>商品名</label>
      <input type="text" name="name">

      <label>ブランド名</label>
      <input type="text" name="brand">

      <label>商品の説明</label>
      <textarea name="description"></textarea>

      <label>販売価格</label>
      <input type="text" name="price" placeholder="¥">

      <button type="submit" class="submit-item">出品する</button>
    </form>
  </main>
</body>

</html>