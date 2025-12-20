<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>住所の変更</title>
    <link rel="stylesheet" href="{{ asset('css/purchase_adress.css') }}">

<body>
    <header class="header">
        <div class="header-left">
            <a href="/"> <img src="{{ asset('images/logo.png') }}" alt="ロゴ"></a>
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
    <main>
        <h1>住所の変更</h1>
        <form method="POST" action="{{ route('purchase.address.update', ['item_id' => $item->id]) }}">
            @csrf

            <label>郵便番号</label>
            <input type="text" name="postal_code" value="{{ old('postal_code', $profile->postal_code) }}">

            <label>住所</label>
            <input type="text" name="address" value="{{ old('address', $profile->address) }}">

            <label>建物名</label>
            <input type="text" name="building" value="{{ old('building', $profile->building) }}">

            <button type="submit" class="update-btn">更新する</button>
        </form>
    </main>

</body>

</html>