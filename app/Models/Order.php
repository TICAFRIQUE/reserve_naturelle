<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
    'num_order',
    'date_order',
    'mt_total',
    'statut',
    'remise',
    'user_id',
    'zone_id',
    'adresse_precise',
    'ville_expedition',
    'tarif_livraison',
    'montant_ttc',
    'ticket_path',
    ];

    protected $casts = [
        'date_order'=>'datetime',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function items(){
        return $this->hasMany(OrderItem::class);
    }

    public function zone(){
        return $this->belongsTo(Zone::class);
    }

    public function products(){
        return $this->belongsToMany(Product::class, 'order_items');
    }

    public function tournees(){
        return $this->belongsToMany(Tournee::class, 'tournee_order');
    }
    
      public function stockMouvements(){
        return $this->morphMany(StockMouvement::class, 'source');
    }
}
