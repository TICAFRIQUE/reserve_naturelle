<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactories;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_creation',
        'user_id',
    ];

    protected $casts = [
        'date_creation'  => 'datetime',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function items(){
        return $this->hasMany(CartItem::class);
    }
}
