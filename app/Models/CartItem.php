<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CartItem extends Model
{
    //
    use HasFactory;

    protected $fillable =[
        'qte',
        'cart_id',
        'product_id',
    ];

    public function cart(){
        return $this->BelongsTo(Cart::class);
    }
    
     public function product(){
        return $this->BelongsTo(Product::class);
    }
}
