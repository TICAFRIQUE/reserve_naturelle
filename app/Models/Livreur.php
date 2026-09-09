<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Livreur extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenom',
        'tel',
        'ville',
        'quartier',
        'statut',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Accesseur pour le nom complet
    public function getFullNameAttribute(){
        return trim($this->prenom . ' ' . $this->nom);
    }
    
    public function tournees(){
        return $this->hasMany(Tournee::class);
    }
}