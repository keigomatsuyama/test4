<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exhibition extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'image_path',
        'item_description',
        'condition_id',
        'price',
    ];

    public function categories()
    {
        return $this->belongsToMany(
            Category::class,
            'category_exhibition',   // ← 中間テーブル名
            'exhibition_id',         // ← exhibition 側の外部キー
            'category_id'            // ← category 側の外部キー
        );
    }
    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class, 'exhibition_id');
    }
    public function likes()
    {
        return $this->hasMany(Like::class, 'exhibition_id');
    }
    public function likedUsers()
    {
        return $this->belongsToMany(User::class, 'likes')->withTimestamps();
    }
}
