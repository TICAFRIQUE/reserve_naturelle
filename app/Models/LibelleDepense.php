<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LibelleDepense extends Model
{
    protected $fillable = [
        'categorie_depense_id', 'nom'
    ];

    public function categorie(){
        return $this->belongsTo(CategorieDepense::class, 'categorie_depense_id');
    }
    
    public function depenses(){
        return $this->hasMany(Depense::class, 'libelle_depense_id');
    }
}