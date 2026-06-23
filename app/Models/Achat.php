<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Achat extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'qte_achetee',
        'mt_total',
        'mt_paye',
        'PU_achat',
        'h_achat',
        'statut',
        'date_achat',
        'num_facture',
        'description',
        'date_paiement',
        'date_reception',
        'fournisseur_id',
        'user_id',
    ];

    protected $casts= [
        'date_achat'=>'date',
        'h_achat'=>'datetime',
        'date_paiement'=>'date',
        'date_reception'=>'date',
    ];

    public function ajustements(){
        return $this->hasMany(Ajustement::class);
    }
     
    public function fournisseur(){
        return $this->BelongsTo(Fournisseur::class);
    }

    public function user(){
        return $this->BelongsTo(User::class);
    }

    public function products(){
        return $this->BelongsToMany(Product::class, 'achat_product')->withPivot('qte','prix_unitaire');
    }
}
