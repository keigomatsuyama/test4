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
      <button type="submit" class="header-link" style="background:none;border:none;">
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

    {{-- 商品画像 --}}
    <label>商品画像 <span class="required">必須</span></label>
    <div class="image-upload">
      <label class="image-select" for="image">画像を選択する</label>
      <input id="image" type="file" name="image" class="file-input">
    </div>
    @error('image')<p class="error">{{ $message }}</p>@enderror

    <h2>商品の詳細</h2>

    {{-- カテゴリー --}}
    <label>カテゴリー <span class="required">必須</span></label>
    <div class="category-container">
      @foreach ($categories as $category)
        <input type="checkbox"
          name="category_id[]"
          id="cat{{ $category->id }}"
          value="{{ $category->id }}"
          {{ in_array($category->id, old('category_id', [])) ? 'checked' : '' }}
          class="category-checkbox">
        <label for="cat{{ $category->id }}" class="category-label">
          {{ $category->name }}
        </label>
      @endforeach
    </div>
    @error('category_id')<p class="error">{{ $message }}</p>@enderror

    {{-- 商品の状態 --}}
    <label>商品の状態 <span class="required">必須</span></label>
    <select name="condition_id">
      <option value="">選択してください</option>
      @foreach ($conditions as $condition)
        <option value="{{ $condition->id }}"
          {{ old('condition_id') == $condition->id ? 'selected' : '' }}>
          {{ $condition->condition }}
        </option>
      @endforeach
    </select>
    @error('condition_id')<p class="error">{{ $message }}</p>@enderror

    {{-- 商品名 --}}
    <label>商品名 <span class="required">必須</span></label>
    <input type="text" name="name" value="{{ old('name') }}">
    @error('name')<p class="error">{{ $message }}</p>@enderror

    {{-- ブランド --}}
    <label>ブランド名</label>
    <input type="text" name="brand" value="{{ old('brand') }}">

    {{-- 商品説明 --}}
    <label>商品の説明 <span class="required">必須</span></label>
    <textarea name="description">{{ old('description') }}</textarea>
    @error('description')<p class="error">{{ $message }}</p>@enderror

    {{-- 価格 --}}
    <label>販売価格 <span class="required">必須</span></label>
    <input type="text" name="price" placeholder="¥" value="{{ old('price') }}">
    @error('price')<p class="error">{{ $message }}</p>@enderror

    <button type="submit" class="submit-item">出品する</button>
  </form>
</main>
</body>
</html>
