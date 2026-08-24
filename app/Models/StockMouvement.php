<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockMouvement extends Model
{
    use HasFactory;

    protected $table = 'stock_mouvements';

    protected $fillable = [
        'product_id',
        'type',
        'sens',
        'quantite',
        'stock_avant',
        'stock_apres',
        'cmp_avant',
        'cmp_apres',
        'source_type',
        'source_id',
        'user_id',
        'notes',
    ];

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    // Relation polymorphique → source du mouvement
    public function source(){
        return $this->morphTo();
    }
}