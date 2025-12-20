<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $item->name }} - 商品詳細</title>
  <link rel="stylesheet" href="{{ asset('css/item.css') }}">
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

  <main class="item-container">

    <div class="item-image-block">
      <div class="item-image">
        <img src="{{ asset('images/' . $item->image_path) }}" alt="{{ $item->name }}" width="300">
      </div>
    </div>

    <div class="item-info-block">
      <h1 class="item-title">{{ $item->name }}</h1>
      <p class="item-brand">COACHTECHセレクト</p>

      <p class="item-price">¥{{ number_format($item->price) }} <span>（税込）</span></p>
      <div class="likes" style="display: flex; align-items: center; gap: 10px; margin: 10px 0;">

        {{-- ♥ / ♡ いいねボタン --}}
        @auth
        @if ($hasLiked)
        <form action="{{ route('items.unlike', $item->id) }}" method="POST" style="margin: 0;">
          @csrf
          @method('DELETE')
          <button type="submit" style="background: none; border: none;">
            <img src="{{ asset('images/like2.png') }}" width="24" alt="いいね済み">
          </button>
        </form>
        @else
        <form action="{{ route('items.like', $item->id) }}" method="POST" style="margin: 0;">
          @csrf
          <button type="submit" style="background: none; border: none;">
            <img src="{{ asset('images/like1.png') }}" width="24" alt="いいねする">
          </button>
        </form>
        @endif
        @else
        <a href="{{ route('login') }}">
          <img src="{{ asset('images/like3.png') }}" width="24" alt="ログインしていいね">
        </a>
        @endauth

        {{-- 件数 --}}
        <span>{{ $likeCount }}</span>

        {{-- 💬 吹き出しアイコン（コメント数） --}}
        <div style="display: flex; align-items: center;">
          <img src="{{ asset('images/like3.png') }}" width="22" alt="コメント数" style="margin-left: 10px;">
          <span style="margin-left: 10px;">{{ $item->comments->count() }}</span>
        </div>
      </div>
      <button class="buy-btn"><a href="{{ route('purchase.show', ['item_id' => $item->id]) }}" class="buy-btn">購入手続きへ</a></button>

      <section class="description">
        <h2>商品説明</h2>
        <p>{{ $item->item_description }}</p>
      </section>

      <section class="detail-info">
        <h2>商品の情報</h2>
        <p><strong>カテゴリー</strong>　
          <span class="tag"> {{ $item->categories->pluck('name')->join(' / ') }}</span>
        </p>
        <p><strong>商品の状態</strong> {{ $item->condition->condition }}</p>
      </section>
      <section class="comments">

        {{-- コメント数 --}}
        <h2>コメント({{ $item->comments->count() }})</h2>

        {{-- コメント一覧 --}}
        @foreach($item->comments as $comment)
        <div class="comment-box">
          <div class="icon"></div>
          <div>
            <p class="username">{{ $comment->user->name }}</p>
            <p class="comment-text">{{ $comment->content }}</p>
          </div>
        </div>
        @endforeach

        {{-- コメント投稿フォーム --}}
        <h3 class="comment-title">商品へのコメント</h3>

        <form action="{{ route('item.comment', $item->id) }}" method="POST">
          @csrf
          <textarea name="content" class="comment-area"></textarea>
          <button type="submit" class="comment-submit">コメントを送信する</button>
        </form>
      </section>
    </div>

  </main>

</body>

</html>