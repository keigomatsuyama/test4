<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExhibitionRequest;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\AddressRequest;
use Illuminate\Http\Request;
use App\Models\Exhibition;
use App\Models\Comment;
use App\Models\Condition;
use App\Models\Purchase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $userId  = auth()->id();
        $tab     = $request->query('tab', 'recommend');
        $keyword = $request->query('keyword');

        // ベースクエリ
        $query = Exhibition::query();

        // マイリスト（イイネしたものだけ）
        if ($tab === 'mylist' && auth()->check()) {
            $query->whereHas('likes', function ($q) {
                $q->where('user_id', auth()->id());
            });
        } else {
            // 自分の出品は除外
            if ($userId) {
                $query->where('user_id', '!=', $userId);
            }
        }

        // 検索（← ここが今まで mylist に効いてなかった）
        if ($keyword) {
            $keywords = preg_split('/\s+/u', $keyword);

            $query->where(function ($q) use ($keywords) {
                foreach ($keywords as $word) {
                    $q->orWhere('name', 'like', "%{$word}%");
                }
            });
        }

        $items = $query->latest()->get();

$soldItemIds = Purchase::pluck('exhibition_id')->toArray();

        return view('top', [
            'exhibitions'    => $items,
            'tab'            => $tab,
            'soldItemIds'  => $soldItemIds,
        ]);
    }

    public function show($id)
    {
        $item = Exhibition::with(['categories', 'comments.user', 'likedUsers'])->findOrFail($id);

        $likeCount = $item->likedUsers->count(); // いいね数
        $hasLiked = false;

        if (Auth::check()) {
            $hasLiked = Auth::user()->likedExhibitions->contains($item->id); // いいね済みか？
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
        'card' => 'クレジットカード',
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
            'success_url' => route('top') . '?success=1',
            'cancel_url' => route('item.show', $item->id),
        ]);

        return redirect($session->url);
    }

    public function edit($item_id)
    {
        $profile = auth()->user()->profile;
        $item = Exhibition::findOrFail($item_id);
        return view('purchase_address', compact('item', 'profile'));
    }

    public function update(AddressRequest $request, $item_id)
    {
        $validated = $request->validated();

        $user = auth()->user();
        $profile = $user->profile ?? $user->profile()->create();

        if ($request->hasFile('profile_image')) {
            $profile->profile_image = $request
                ->file('profile_image')
                ->store('profile_images', 'public');
        }
        $profile->address     = $validated['address'];
        $profile->building    = $validated['building'] ?? null;

        $profile->save();

        return redirect("/purchase/{$item_id}")
            ->with('success', '住所を更新しました');
    }

    public function mypage(Request $request)
    {
        $page = $request->query('page', 'sell');

        if (!in_array($page, ['sell', 'buy'], true)) {
            abort(404);
        }

        $user = auth()->user();

        // 常に Collection を渡す
        $soldItems = collect();
        $boughtItems = collect();

        // 出品した商品一覧
        if ($page === 'sell') {
            $soldItems = $user->exhibitions()->get();
        }

        // 購入した商品一覧
       if ($page === 'buy') {
    $boughtItems = $user->purchases()
        ->with('item')
        ->get()
        ->pluck('item')
        ->filter()
        ->unique('id')   // ★ これを追加
        ->values();      // インデックス整理（任意）
}


        return view('mypage', compact('page', 'soldItems', 'boughtItems'));
    }

    public function sell()
    {
        $categories = DB::table('categories')->get();
        $conditions = Condition::all();
        return view('sell', compact('categories', 'conditions'));
    }
    public function store(ExhibitionRequest $request)
    { {
            // バリデーション
            $validated = $request->validate([
                'name'         => 'required|string|max:255',
                'brand'        => 'nullable|string|max:255',
                'description'  => 'required|string',
                'price'        => 'required|integer|min:1',
                'condition_id' => 'required|integer|exists:conditions,id',
                'category_id'  => 'required|array',
                'category_id.*' => 'integer|exists:categories,id',
                'image'        => 'required|image|max:2048',
            ]);

            // 画像保存
            $path = $request->file('image')->store('items', 'public');

            // exhibition を保存
            $item = Exhibition::create([
                'user_id'          => auth()->id(),
                'name'             => $validated['name'],
                'image_path'       => $path,
                'item_description' => $validated['description'],
                'condition_id'     => $validated['condition_id'],
                'price'            => $validated['price'],
            ]);

            // カテゴリを保存（中間テーブル）
            $item->categories()->attach($validated['category_id']);

            // 完了後にトップやマイページへリダイレクト
            return redirect('/mypage')->with('success', '商品を出品しました！');
        }
    }
}
