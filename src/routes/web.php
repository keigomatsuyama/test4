<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
// Fortify の login/register ページは自動なので不要
// POST はあなたのコントローラでOK

Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');
Route::post('/mypage/profile', [UserController::class, 'updateProfile'])
    ->name('profile.update');
Route::get('/', [ItemController::class, 'index'])->name('top');
Route::get('/item/{id}', [ItemController::class, 'show'])->name('item.show');
Route::middleware(['auth','verified'])->group(function () {
    Route::get('/mypage/profile', [UserController::class, 'profile'])->name('profile');
    Route::get('/mypage', [ItemController::class, 'myPage'])->name('mypage');
    Route::post('/items/{id}/like', [ItemController::class, 'like'])->name('items.like');
    Route::delete('/items/{id}/unlike', [ItemController::class, 'unlike'])->name('items.unlike');
    Route::post(
        '/item/{item}/comment',
        [ItemController::class, 'addComment']
    )->name('item.comment');

    Route::get('/purchase/address/{item_id}', [ItemController::class, 'edit'])
        ->name('purchase.address.edit');

    // 更新処理
    Route::post('/purchase/address/{item_id}', [ItemController::class, 'update'])
        ->name('purchase.address.update');
    Route::get('/purchase/{item_id}', [ItemController::class, 'purchase'])->name('purchase.show');
    Route::post('/purchase/store', [ItemController::class, 'purchasestore'])->name('purchase.store');

    Route::get('/sell', [ItemController::class, 'sell'])->name('item.sell');
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');
});
