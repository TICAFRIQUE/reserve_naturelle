<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RetourFournisseur extends Model
{
    use HasFactory;

    protected $table = 'retours_fournisseur';

    protected $fillable = [
        'achat_id',
        'fournisseur_id',
        'user_id',
        'date_retour',
        'montant_avoir',
        'statut_avoir',
        'motif',
    ];

    protected $casts = [
        'date_retour' => 'date',
    ];

    public function achat(){
        return $this->belongsTo(Achat::class);
    }

    public function fournisseur(){
        return $this->belongsTo(Fournisseur::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function produits(){
        return $this->hasMany(RetourFournisseurProduit::class);
    }

    public function stockMouvements(){
        return $this->morphMany(StockMouvement::class, 'source');
    }
}