<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RetourClient extends Model
{
    use HasFactory;

    protected $table = 'retours_client';

    protected $fillable = [
        'order_id',
        'user_id',
        'date_retour',
        'montant_avoir',
        'statut_avoir',
        'date_expiration_avoir',
        'motif',
    ];

    protected $casts = [
        'date_retour'            => 'date',
        'date_expiration_avoir'  => 'date',
    ];

    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function produits(){
        return $this->hasMany(RetourClientProduit::class);
    }

    public function stockMouvements(){
        return $this->morphMany(StockMouvement::class, 'source');
    }
}