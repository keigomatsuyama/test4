取引が完了しました。

商品名：
{{ $transaction->item->name }}

取引相手：
{{ $partner->profile->username }}

商品ページ：
{{ url('/item/' . $transaction->item->id) }}

ご利用ありがとうございました。