<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'exhibition_id', // 商品ID（ある場合）
        'payment_method',
        'shipping_name',
        'shipping_postal',
        'shipping_address',
        'shipping_building',
        'shipping_phone',
        'total_price',
        'status', // 任意：注文状態
    ];

    // 購入者とのリレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 商品（出品）とのリレーション
    public function item()
    {
        return $this->belongsTo(Exhibition::class, 'exhibition_id');
    }
}
