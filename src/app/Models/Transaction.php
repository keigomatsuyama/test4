<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'seller_id',
        'buyer_id',
        'status',
    ];

    public function item()
    {
        return $this->belongsTo(
            Exhibition::class,
            'item_id'
        );
    }

    public function seller()
    {
        return $this->belongsTo(
            User::class,
            'seller_id'
        );
    }

    public function buyer()
    {
        return $this->belongsTo(
            User::class,
            'buyer_id'
        );
    }
    public function messages()
    {
        return $this->hasMany(
            TransactionMessage::class
        );
    }
}
