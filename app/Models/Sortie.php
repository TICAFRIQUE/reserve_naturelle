<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sortie extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'qte_sortie',
        'type_sortie',
        'date_sortie',
        'h_sortie',
        'description',
    ];

    protected $casts = [
        'date_sortie' => 'date',
        'h_sortie' => 'datetime',
    ];

    public function product(){
        return $this->belongsTo(Product::class);
    }
    
    public function user(){
        return $this->belongsTo(User::class);
    }
}