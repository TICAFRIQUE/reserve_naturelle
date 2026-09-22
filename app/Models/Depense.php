<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depense extends Model
{
    use HasFactory;

    protected $fillable = [
        'categorie_depense_id',
        'libelle', 'description',
        'montant', 'date_depense',
        'mode_paiement',
        'reference',
        'created_by',
    ];

    protected $casts = [
        'date_depense' => 'date',
        'montant'      => 'integer',
    ];

    public function categorie(){
        return $this->belongsTo(CategorieDepense::class, 'categorie_depense_id');
    }

    public function createur(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeEntre($query, $from, $to){
        return $query->when($from, fn ($q) => $q->whereDate('date_depense', '>=', $from))
                     ->when($to, fn ($q) => $q->whereDate('date_depense', '<=', $to));
    }
}