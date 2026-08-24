<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RetourFournisseurProduit extends Model
{
    use HasFactory;

    protected $table = 'retour_fournisseur_produits'; //Ligne retour fournisseur

    protected $fillable = [
        'retour_fournisseur_id',
        'product_id',
        'qte_retournee',
        'prix_unitaire',
        'motif',
    ];

    public function retourFournisseur(){
        return $this->belongsTo(RetourFournisseur::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }
}