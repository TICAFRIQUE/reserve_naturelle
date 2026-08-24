<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InventaireProduct extends Model
{
    use HasFactory;

    protected $table = 'inventaire_produits';

    protected $fillable = [
        'inventaire_id',
        'product_id',
        'qte_theorique',
        'qte_reelle',
        'ecart',
    ];

    public function inventaire()
    {
        return $this->belongsTo(Inventaire::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}