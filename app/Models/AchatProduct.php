<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AchatProduct extends Model
{
    use HasFactory;

    protected $table = 'achat_product';

    protected $fillable = [
        'achat_id',
        'product_id',
        'qte_commandee',
        'qte_recue',
        'prix_unitaire',
    ];

    public function achat(){
        return $this->belongsTo(Achat::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }

    // Quantité restante à recevoir
    public function getReliquatAttribute(): int{
        return $this->qte_commandee - $this->qte_recue;
    }
}