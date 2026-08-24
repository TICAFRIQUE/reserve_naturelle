<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RetourClientProduit extends Model
{
    use HasFactory;

    protected $table = 'retour_client_produits';//Ligne retour client

    protected $fillable = [
        'retour_client_id',
        'product_id',
        'qte_retournee',
        'prix_unitaire',
        'motif',
    ];

    public function retourClient()
    {
        return $this->belongsTo(RetourClient::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}