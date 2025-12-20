<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];
    public function exhibitions()
    {
        return $this->belongsToMany(Exhibition::class, 'category_exhibition', 'category_id', 'exhibition_id');
    }
}
