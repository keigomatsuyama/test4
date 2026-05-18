<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionMessage extends Model
{
    protected $fillable = [
        'transaction_id',
        'user_id',
        'message',
        'image_path',
        'is_read',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}