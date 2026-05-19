{{-- resources/views/transaction.blade.php --}}

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>取引画面</title>

  <link rel="stylesheet" href="{{ asset('css/transaction.css') }}">
</head>

<body>

  <header class="header">

    <div class="header-left">

      <a href="/">

        <img
          src="{{ asset('images/logo.png') }}"
          alt="ロゴ">

      </a>

    </div>

  </header>

  <div class="transaction-layout">

    {{-- ================= 左サイド ================= --}}
    <aside class="transaction-sidebar">

      <h2 class="sidebar-title">
        その他の取引
      </h2>

      @foreach($buyTransactions as $sideTransaction)

      <a
        href="{{ route('transactions.show', $sideTransaction->id) }}"
        class="sidebar-item">

        {{ $sideTransaction->item->name }}

      </a>

      @endforeach

    </aside>

    {{-- ================= 右側 ================= --}}
    <main class="transaction-main">

      {{-- header --}}
      <div class="transaction-header">

        <div class="transaction-user">

          <div class="user-avatar"></div>

          <h2>
            「{{ $partner->profile->username ?? 'ユーザー名' }}」さんとの取引画面
          </h2>

        </div>
<button
  type="button"
  class="complete-btn"
  onclick="
    document.getElementById('review-modal').style.display='block';
  ">

  取引を完了する

</button>


      </div>

      {{-- 商品情報 --}}
      <div class="transaction-item">

        @if(Str::startsWith($transaction->item->image_path, 'items/'))

        <img
          src="{{ asset('storage/' . $transaction->item->image_path) }}"
          alt="">

        @else

        <img
          src="{{ asset('images/' . $transaction->item->image_path) }}"
          alt="">

        @endif

        <div class="transaction-item-info">

          <h1>
            {{ $transaction->item->name }}
          </h1>

          <p>
            ¥{{ number_format($transaction->item->price) }}
          </p>

        </div>

      </div>

      {{-- メッセージ一覧 --}}
      <div class="messages">

        @foreach($messages as $message)

        @if($message->user_id === Auth::id())

        {{-- 自分 --}}
        <div class="message-right-wrap">

          <div class="message-user-right">

            <span>
              {{ $message->user->profile->username ?? 'ユーザー名' }}
            </span>

           <div class="message-avatar">

    @if($message->user->profile?->profile_image)

        <img
            src="{{ asset('storage/' . $message->user->profile->profile_image) }}"
            alt=""
            style="
                width:50px;
                height:50px;
                border-radius:50%;
                object-fit:cover;
            ">

    @endif

</div>

          </div>

          {{-- メッセージ表示 --}}
          <div
            class="message-right"
            id="message-text-{{ $message->id }}">

            {{ $message->message }}
  @if($message->image_path)

  <img
    src="{{ asset('storage/' . $message->image_path) }}"
    style="max-width:200px; margin-top:10px;">

  @endif

          </div>

          {{-- 編集フォーム --}}
          <form
            id="edit-form-{{ $message->id }}"
            action="{{ route('message.update', $message->id) }}"
            method="POST"
              enctype="multipart/form-data"
            style="display:none;"
            class="edit-form">

            @csrf
            @method('PUT')

            <input
              type="text"
              name="message"
              value="{{ $message->message }}"
              class="edit-input">
 <input
    type="file"
    name="image">

  {{-- 現在画像 --}}
  @if($message->image_path)

  <div style="margin-top:10px;">

    <img
      src="{{ asset('storage/' . $message->image_path) }}"
      style="max-width:120px;">

  </div>

  {{-- 画像削除 --}}
  <label>

    <input
      type="checkbox"
      name="delete_image"
      value="1">

    画像を削除

  </label>

  @endif
            <button type="submit"
            class="edit-save-btn">
              保存
            </button>

          </form>

          {{-- 編集・削除 --}}
          <div class="message-actions">

            {{-- 編集 --}}
            <button
              type="button"
              class="message-edit"
              onclick="showEditForm({{ $message->id }})">

              編集

            </button>

            {{-- 削除 --}}
            <form
              action="{{ route('message.destroy', $message->id) }}"
              method="POST"
              onsubmit="return confirm('削除しますか？');">

              @csrf
              @method('DELETE')

              <button
                type="submit"
                class="message-delete">

                削除

              </button>

            </form>

          </div>

        </div>

        @else

        {{-- 相手 --}}
        <div class="message-left-wrap">

          <div class="message-user-left">

            <div class="message-avatar"></div>

            <span>
              {{ $message->user->profile->username ?? 'ユーザー名' }}
            </span>

          </div>

          <div class="message-left">

            {{ $message->message }}

@if($message->image_path)

<img
    src="{{ asset('storage/' . $message->image_path) }}"
    style="
        max-width:200px;
        margin-top:10px;
        border-radius:10px;
    ">

@endif
          </div>

        </div>

        @endif

        @endforeach

      </div>

      {{-- 入力欄 --}}
      <div class="message-errors">

    @error('message')

    <p class="error-message">
        {{ $message }}
    </p>

    @enderror

    @error('image')

    <p class="error-message">
        {{ $message }}
    </p>

    @enderror

</div>
            <form
                action="{{ route('message.store', $transaction->id) }}"
                method="POST"
                class="message-form"
                enctype="multipart/form-data">

                @csrf

                <input
                    type="text"
                    name="message"
                    placeholder="取引メッセージを記入してください">


                <label class="image-btn">

                    画像を追加

                    <input
                        type="file"
                        name="image"
                        hidden>

                </label>

                <button
                    type="submit"
                    class="send-btn">

                    ➤

                </button>

            </form>

    </main>

  </div>

  {{-- ================= 評価モーダル ================= --}}
<div
  id="review-modal"
  style="
    display:none;
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.5);
    z-index:99999;
  ">

  <div
    style="
      width:520px;
      background:#f6f3e7;
      margin:120px auto;
      border-radius:8px;
      overflow:hidden;
      border:1px solid #999;
    ">

    {{-- タイトル --}}
    <div
      style="
        padding:20px 25px;
        border-bottom:1px solid #999;
        font-size:32px;
        font-weight:bold;
      ">

      取引が完了しました。

    </div>

    {{-- 本文 --}}
    <div
      style="
        padding:20px 25px;
      ">

      <p
        style="
          color:#666;
          font-size:14px;
          margin-bottom:20px;
        ">

        今回の取引相手はどうでしたか？

      </p>

      <form
        action="{{ route('reviews.store', $transaction->id) }}"
        method="POST">

        @csrf

        {{-- 星 --}}
        <div
          style="
            display:flex;
            gap:10px;
            margin-bottom:25px;
          ">

          @for($i = 1; $i <= 5; $i++)

          <span
            class="star"
            onclick="selectStar({{ $i }})"
            style="
              font-size:70px;
              color:#d9d9d9;
              cursor:pointer;
            ">

            ★

          </span>

          @endfor

        </div>

        <input
          type="hidden"
          name="rating"
          id="rating-value">

        {{-- ボタン --}}
        <div
          style="
            border-top:1px solid #999;
            padding-top:20px;
            text-align:right;
          ">

          <button
            type="submit"
            style="
              background:#ff8b8b;
              color:white;
              border:none;
              padding:12px 25px;
              border-radius:5px;
              font-size:20px;
              cursor:pointer;
            ">

            送信する

          </button>

        </div>

      </form>

    </div>

  </div>

</div>

<script>
  function showEditForm(messageId)
  {
    // メッセージ非表示
    document.getElementById(
      'message-text-' + messageId
    ).style.display = 'none';

    // 編集フォーム表示
    document.getElementById(
      'edit-form-' + messageId
    ).style.display = 'block';
  }

  function selectStar(rating)
  {
    document.getElementById(
      'rating-value'
    ).value = rating;

    const stars =
      document.querySelectorAll('.star');

    stars.forEach((star, index) =>
    {
      if(index < rating)
      {
        star.style.color = '#ffe100';
      }
      else
      {
        star.style.color = '#d9d9d9';
      }
    });
  }

</script>

</body>

</html>