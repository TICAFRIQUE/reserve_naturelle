<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategorieDepense extends Model
{
    protected $fillable = ['nom'];

    public function libelles(){
        return $this->hasMany(LibelleDepense::class, 'categorie_depense_id');
    }

    // Dépenses de la famille, via ses libellés
    public function depenses(){
        return $this->hasManyThrough(
            Depense::class,
            LibelleDepense::class,
            'categorie_depense_id', // FK sur libelle_depenses
            'libelle_depense_id'    // FK sur depenses
        );
    }
}