<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_prod',
        'prix_vente',
        'designation',
        'description',
        'qte_dispo',
        'stock_minimum',
        'cmp',
        'image_path',
        'category_id',
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function cartItems(){
        return $this->hasMany(CartItem::class);
    }

    public function orderItems(){
        return $this->hasMany(OrderItem::class);
    }

    public function orders(){
        return $this->belongsToMany(Order::class, 'order_items');
    }

    public function achatProducts(){
        return $this->hasMany(AchatProduct::class);
    }

    public function inventaireProducts(){
        return $this->hasMany(InventaireProduct::class);
    }

    public function stockMouvements(){
        return $this->hasMany(StockMouvement::class);
    }

    public function getSousSeuilAttribute(): bool{
        return $this->qte_dispo <= $this->stock_minimum;
    }
    public function scopeSearch($query, string $term){
    
    return $query->where(function ($q) use ($term) {
        $q->where('designation', 'like', "%{$term}%")->orWhere('reference_prod', 'like', "%{$term}%");
    });
}
}