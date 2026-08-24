<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inventaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'user_id',
        'statut',
        'date_inventaire',
        'notes',
    ];

    protected $casts = [
        'date_inventaire' => 'date',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function produits(){
        return $this->hasMany(InventaireProduct::class, 'inventaire_id');
    }

    public function stockMouvements(){
        return $this->morphMany(StockMouvement::class, 'source');
    }
}