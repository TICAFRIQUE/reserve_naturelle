<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategorieDepense extends Model
{
    protected $fillable = ['nom'];

    public function depenses()
    {
        return $this->hasMany(Depense::class);
    }
}