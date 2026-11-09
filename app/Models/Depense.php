<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depense extends Model
{
    use HasFactory;

    protected $fillable = [
        'libelle_depense_id',
        'montant',
        'date_depense',
        'created_by',
    ];

    protected $casts = [
        'date_depense' => 'date',
        'montant'      => 'integer',
    ];

    public function libelle(){
        return $this->belongsTo(LibelleDepense::class, 'libelle_depense_id');
    }

    public function createur(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeEntre($query, $from, $to){
        return $query->when($from, fn ($q) => $q->whereDate('date_depense', '>=', $from))
                     ->when($to, fn ($q) => $q->whereDate('date_depense', '<=', $to));
    }
}