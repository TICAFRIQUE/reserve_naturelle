<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    //
    use hasFactory;

    protected $fillable = [
        'reference_prod',
        'prix_vente',
        'designation',
        'description',
        'qte_dispo',
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

    public function achats(){
        return $this->belongsToMany(Achat::class, 'achat_product')->withPivot('qte','prix_unitaire');
    }

    public function inventaires(){
        return $this->belongsToMany(Inventaire::class, 'inventaire_product')->withPivot('qte', 'type_mvt', 'date_mvt', 'h_mvt');
    }
}
