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
      <a href="/"><img src="{{ asset('images/logo.png') }}" alt="ロゴ"></a>
    </div>

    <div class="header-center">
      <form action="{{ route('top') }}" method="GET">
        <input type="text" name="keyword" class="search-box"
          placeholder="なにをお探しですか？" value="{{ request('keyword') }}">
      </form>
    </div>

    <div class="header-right">
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="header-link" style="background:none;border:none;cursor:pointer;">
          ログアウト
        </button>
      </form>
      <a href="/mypage" class="header-link">マイページ</a>
      <a href="/sell" class="sell-btn">出品</a>
    </div>
  </header>
<main class="profile">

    {{-- プロフィール --}}
    <div class="profile-header">

        <div class="profile-left">

            <div class="avatar">

                @if(Auth::user()->profile && Auth::user()->profile->profile_image)

                <img
                    src="{{ asset('storage/' . Auth::user()->profile->profile_image) }}"
                    alt="プロフィール画像">

                @endif

            </div>

            <div class="profile-info">

                <h2>
                    {{ Auth::user()->profile->username ?? 'ユーザー名' }}
                </h2>

                <div class="rating-stars">

                    @for($i = 1; $i <= 5; $i++)

                        @if($i <= ($averageRating ?? 0))

                            <span class="star filled">
                                ★
                            </span>

                        @else

                            <span class="star">
                                ★
                            </span>

                        @endif

                    @endfor

                </div>

            </div>

        </div>

        <a href="/mypage/profile" class="edit-btn">
            プロフィールを編集
        </a>

    </div>
    <div class="tabs">

      {{-- 出品した商品 --}}
      <a href="{{ route('mypage', ['page' => 'sell']) }}"
        class="tab-label {{ $page === 'sell' ? 'active' : '' }}">
        出品した商品
      </a>

      {{-- 購入した商品 --}}
      <a href="{{ route('mypage', ['page' => 'buy']) }}"
        class="tab-label {{ $page === 'buy' ? 'active' : '' }}">
        購入した商品
      </a>

      {{-- 取引中の商品 --}}
      <a href="{{ route('mypage', ['page' => 'transaction']) }}"
        class="tab-label {{ $page === 'transaction' ? 'active' : '' }}">
        取引中の商品
        @if($transactions->count() > 0)
  <span class="tab-badge">
    {{ $transactions->count() }}
  </span>
@endif
      </a>

    </div>

    {{-- コンテンツ --}}
    <div class="content">

      {{-- 出品した商品 --}}
      @if ($page === 'sell')

      <div class="items-wrapper">

        @forelse ($sellingItems as $item)

        <div class="item-card">

          @if (Str::startsWith($item->image_path, 'items/'))
          <img src="{{ asset('storage/' . $item->image_path) }}" alt="商品画像">
          @else
          <img src="{{ asset('images/' . $item->image_path) }}" alt="商品画像">
          @endif

          <p class="item-name">{{ $item->name }}</p>
          <p class="item-price">
            ¥{{ number_format($item->price) }}
          </p>
        </div>

        @empty

        <p class="empty-message">
          出品した商品はありません。
        </p>

        @endforelse

      </div>

      @endif

      {{-- 購入した商品 --}}
      @if ($page === 'buy')

      <div class="items-wrapper">

        @forelse ($boughtItems as $purchase)

        @php
        $item = $purchase->item;
        @endphp

        @if ($item)

        <div class="item-card">

          @if (Str::startsWith($item->image_path, 'items/'))
          <img src="{{ asset('storage/' . $item->image_path) }}" alt="商品画像">
          @else
          <img src="{{ asset('images/' . $item->image_path) }}" alt="商品画像">
          @endif

          <p class="item-name">{{ $item->name }}</p>

          <p class="item-price">
            ¥{{ number_format($item->price) }}
          </p>

        </div>

        @endif

        @empty

        <p class="empty-message">
          購入した商品はありません。
        </p>

        @endforelse

      </div>

      @endif

      {{-- 取引中の商品 --}}
      @if ($page === 'transaction')

      <div class="items-wrapper">

        @forelse ($transactions as $transaction)

        @php
        $item = $transaction->item;

        $partner = $transaction->seller_id === Auth::id()
        ? $transaction->buyer
        : $transaction->seller;
        @endphp

      <a href="{{ route('transactions.show', $transaction->id) }}"
          class="item-link">

          <div class="item-card">
            @if($transaction->unread_count > 0)

            <span class="item-badge">
              {{ $transaction->unread_count }}
            </span>

            @endif
            @if (Str::startsWith($item->image_path, 'items/'))
            <img src="{{ asset('storage/' . $item->image_path) }}" alt="商品画像">
            @else
            <img src="{{ asset('images/' . $item->image_path) }}" alt="商品画像">
            @endif

            <p class="item-name">
              {{ $item->name }}
            </p>

            <p class="transaction-user">
              取引相手：
              {{ $partner->profile->username ?? 'ユーザー' }}
            </p>

          </div>

        </a>

        @empty

        <p class="empty-message">
          取引中の商品はありません。
        </p>

        @endforelse

      </div>

      @endif
    </div>

  </main>

</body>

</html>