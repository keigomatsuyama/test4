<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements \Illuminate\Contracts\Auth\MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }
    public function exhibitions()
    {
        return $this->hasMany(Exhibition::class);
    }
    public function likes()
    {
        return $this->hasMany(Like::class, 'user_id');
    }
    public function likedExhibitions()
    {
        return $this->belongsToMany(Exhibition::class, 'likes')->withTimestamps();
    }
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
    public function averageRating()
{
    // 出品者として貰った評価
    $sellerRatings =
        Transaction::where(
            'seller_id',
            $this->id
        )
        ->whereNotNull('buyer_rating')
        ->pluck('buyer_rating');

    // 購入者として貰った評価
    $buyerRatings =
        Transaction::where(
            'buyer_id',
            $this->id
        )
        ->whereNotNull('seller_rating')
        ->pluck('seller_rating');

    $ratings =
        $sellerRatings->merge($buyerRatings);

    // 評価なし
    if($ratings->count() === 0)
    {
        return null;
    }

    // 平均を四捨五入
    return round(
        $ratings->avg()
    );
}
    }
