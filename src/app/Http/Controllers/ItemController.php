<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExhibitionRequest;
use App\Http\Requests\CommentRequest;
use
    App\Http\Requests\AddressRequest;
use App\Http\Requests\TransactionMessageRequest;
use Illuminate\Http\Request;
use App\Models\Exhibition;
use App\Models\Comment;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Transaction;
use App\Models\TransactionMessage;
use App\Models\Purchase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\TransactionCompletedMail;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $tab     = $request->query('tab', 'recommend');
        $keyword = $request->query('keyword');
        $canShowMylist = false;

        if (
            $tab === 'mylist'
            && auth()->check()
            && auth()->user()->hasVerifiedEmail()
        ) {
            $canShowMylist = true;
        }
        $query = Exhibition::query();

        // ★ ログイン中は「自分の出品」を除外
        if (auth()->check()) {
            $query->where('user_id', '!=', auth()->id());
        }

        // マイリスト
        // マイリスト（ログイン ＋ メール認証済みのみ）
        if ($tab === 'mylist' && auth()->check() && !auth()->user()->hasVerifiedEmail()) {
            abort(403); // または redirect
        }
        if (
            $tab === 'mylist'
            && auth()->check()
            && auth()->user()->hasVerifiedEmail()
        ) {
            $query->whereHas('likes', function ($q) {
                $q->where('user_id', auth()->id());
            });
        }


        // キーワード検索
        if ($keyword) {
            $keywords = preg_split('/\s+/u', $keyword);
            $query->where(function ($q) use ($keywords) {
                foreach ($keywords as $word) {
                    $q->where('name', 'like', "%{$word}%");
                }
            });
        }
        $query->orderByRaw(
            'CASE WHEN exhibitions.id IN (SELECT exhibition_id FROM purchases) THEN 1 ELSE 0 END'
        );
        $items = $query
            ->orderBy('created_at', 'desc')
            ->get();

        $soldItemIds = Purchase::pluck('exhibition_id')->toArray();

        return view('top', [
            'exhibitions' => $items,
            'tab' => $tab,
            'soldItemIds' => $soldItemIds,
            'canShowMylist' => $canShowMylist,
        ]);
    }
    public function show($id)
    {
        $item = Exhibition::with([
            'categories',
            'comments.user',
            'likedUsers'
        ])->findOrFail($id);

        $likeCount = $item->likedUsers->count();

        $hasLiked = false;

        if (Auth::check()) {
            $hasLiked = Auth::user()
                ->likedExhibitions
                ->contains($item->id);
        }

        // ★ transaction取得
        $transactionId = request('transaction');

        // ★ 未読を0に
        if ($transactionId) {

            $transaction = Transaction::find($transactionId);

            if ($transaction) {

                $transaction->increment('unread_count');
            }
        }

        return view('item', [
            'item' => $item,
            'likeCount' => $likeCount,
            'hasLiked' => $hasLiked,
        ]);
    }

    public function like($id)
    {
        Auth::user()->likedExhibitions()->syncWithoutDetaching([$id]);
        return back();
    }

    public function unlike($id)
    {
        Auth::user()->likedExhibitions()->detach($id);
        return back();
    }
    public function addComment(CommentRequest $request, Exhibition $item)
    {
        Comment::create([
            'user_id' => auth()->id(),
            'exhibition_id' => $item->id,  // ← item じゃなく exhibition カラムだけど名前は item で受ける
            'content' => $request->content,
        ]);

        return back();
    }

    public function purchase(Request $request, $item_id)
    {
        session(['purchase_item_id' => $item_id]); // 購入中の商品IDを保持

        $item = Exhibition::findOrFail($item_id);
        $profile = auth()->user()->profile;  // ← 常に最新住所を取得

        $selected = $request->payment_method; // 支払い方法GET反映
        $paymentLabels = [
            'card' => 'カード払い',
            'konbini' => 'コンビニ払い',
        ];

        $selectedLabel = $paymentLabels[$selected] ?? '';

        return view('purchase', compact('item', 'profile', 'selected', 'selectedLabel'));
    }
    public function purchasestore(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:exhibitions,id',
            'payment_method' => 'required|in:card,konbini',
        ]);

        $item = Exhibition::findOrFail($validated['item_id']);

        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => [$validated['payment_method']],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->name,
                    ],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('purchase.success') . '?item_id=' . $item->id,
            'cancel_url' => route('item.show', $item->id),
        ]);

        // ★ここで Stripe に飛ばす
        return redirect($session->url);
    }

    public function success(Request $request)
    {
        $item = Exhibition::findOrFail($request->item_id);
        $profile = auth()->user()->profile;

        // 二重購入防止
        if (Purchase::where('exhibition_id', $item->id)->exists()) {
            return redirect()->route('top');
        }

        Purchase::create([
            'user_id'           => auth()->id(),
            'exhibition_id'     => $item->id,
            'payment_method'    => 'card',
            'shipping_name'     => $profile->username ?? auth()->user()->name,
            'shipping_postal'   => $profile->postal_code ?? '000-0000',
            'shipping_address'  => $profile->address ?? '未設定',
            'shipping_building' => $profile->building,
            'shipping_phone'    => $profile->phone ?? '0000000000',
            'total_price'       => $item->price,
        ]);

        // ★ 取引作成
        $transaction = Transaction::create([
            'item_id'   => $item->id,
            'seller_id' => $item->user_id,
            'buyer_id'  => auth()->id(),
            'status'    => 'trading',
        ]);

        return redirect()->route('top', [
            'id' => $item->id,
            'transaction' => $transaction->id,
        ])->with('success', '購入が完了しました');
    }
    public function transactionShow($id)
    {
        $transaction = Transaction::with([
            'item',
            'buyer.profile',
            'seller.profile'
        ])->findOrFail($id);

        $isSeller =
            $transaction->seller_id === Auth::id();

        $partner =
            $isSeller
            ? $transaction->buyer
            : $transaction->seller;

        // 出品中の取引
        $sellTransactions = Transaction::where(
            'seller_id',
            Auth::id()
        )->get();

        // 購入中の取引
        $buyTransactions = Transaction::where(
            'buyer_id',
            Auth::id()
        )->get();

        $messages = $transaction->messages;

        if ($isSeller) {

            return view('transaction_sell', compact(
                'transaction',
                'partner',
                'messages',
                'sellTransactions',
                'buyTransactions',
                'isSeller',
            ));
        }

        return view('transaction_buy', compact(
            'transaction',
            'partner',
            'messages',
            'sellTransactions',
            'buyTransactions',
            'isSeller',
        ));
    }
    public function messageEdit(TransactionMessage $message)
    {
        if ($message->user_id !== Auth::id()) {
            abort(403);
        }

        return view('message.edit', compact('message'));
    }
    public function messageUpdate(
        Request $request,
        TransactionMessage $message
    ) {
        if ($message->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'message' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        // テキスト更新
        $message->message = $request->message;

        // 画像削除
        if ($request->has('delete_image')) {

            // storage画像削除
            if ($message->image_path) {

                \Storage::disk('public')
                    ->delete($message->image_path);
            }

            // DBもnull
            $message->image_path = null;
        }

        // 新画像アップロード
        if ($request->hasFile('image')) {

            // 古い画像削除
            if ($message->image_path) {

                \Storage::disk('public')
                    ->delete($message->image_path);
            }

            // 新画像保存
            $message->image_path = $request
                ->file('image')
                ->store('messages', 'public');
        }

        $message->save();

        return redirect()->back();
    }
    public function mypage(Request $request)
    {
        $page = $request->query('page', 'sell');

        $sellingItems = collect();
        $boughtItems = collect();
        $transactions = collect();

        if ($page === 'sell') {

            $sellingItems = Exhibition::where(
                'user_id',
                Auth::id()
            )->get();
        } elseif ($page === 'buy') {

            $boughtItems = Purchase::where(
                'user_id',
                Auth::id()
            )->get();
        } elseif ($page === 'transaction') {
            $transactions = Transaction::with([
                'item',
                'seller.profile',
                'buyer.profile',
            ])
                ->where(function ($query) {

                    // 出品者
                    $query->where(function ($q) {

                        $q->where('seller_id', Auth::id())
                            ->whereIn('status', [
                                'trading',
                                'buyer_completed'
                            ]);
                    })

                        // 購入者
                        ->orWhere(function ($q) {

                            $q->where('buyer_id', Auth::id())
                                ->where('status', 'trading');
                        });
                })
                ->get();
        }
        $averageRating =
            Auth::user()->averageRating();
        return view('mypage', compact(
            'page',
            'sellingItems',
            'boughtItems',
            'transactions',
            'averageRating'
        ));
    }
    public function sell()
    {
        $conditions = Condition::all();

        $categories = Category::all();

        return view('sell', compact(
            'conditions',
            'categories'
        ));
    }
    public function store(Request $request)
    {
        $imagePath = $request->file('image')
            ->store('items', 'public');

        Exhibition::create([

            'user_id' => Auth::id(),

            'condition_id' => $request->condition_id,

            'name' => $request->name,

            'image_path' => $imagePath,

            // ★ここ
            'item_description' => $request->description,

            'price' => $request->price,

        ]);

        return redirect()->route('top');
    }
    public function messageStore(
        TransactionMessageRequest $request,
        $id
    ) {
        $message = new TransactionMessage();

        $message->transaction_id = $id;

        $message->user_id = Auth::id();

        $message->message = $request->message;

        if ($request->hasFile('image')) {

            $imagePath = $request
                ->file('image')
                ->store('messages', 'public');

            $message->image_path = $imagePath;
        }

        $message->save();

        return back();
    }
    public function messageDestroy(
        TransactionMessage $message
    ) {
        if ($message->user_id !== Auth::id()) {
            abort(403);
        }

        // 画像削除
        if ($message->image_path) {

            Storage::disk('public')
                ->delete($message->image_path);
        }

        // メッセージ削除
        $message->delete();

        return redirect()->back();
    }
    public function reviewstore(
        Request $request,
        Transaction $transaction
    ) {
        $transaction->load('seller');
        $request->validate([
            'rating' => [
                'required',
                'integer',
                'min:1',
                'max:5'
            ],
        ]);

        if (Auth::id() == $transaction->buyer_id) {
            $transaction->buyer_rating =
                $request->rating;

            $transaction->status =
                'buyer_completed';
            Mail::to($transaction->seller->email)
                ->send(
                    new TransactionCompletedMail(
                        $transaction
                    )
                );
        } else {
            $transaction->seller_rating =
                $request->rating;

            $transaction->status =
                'completed';
        }

        $transaction->save();

        return redirect()->route('top');
    }
}
