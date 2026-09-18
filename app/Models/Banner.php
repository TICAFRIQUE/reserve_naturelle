<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'titre',
        'titre_span',
        'sous_titre',
        'texte_bouton',
        'lien_bouton',
        'image_path',
        'actif',
    ];

    // Récupérer la bannière active (une seule)
    public static function active(){
        return self::where('actif', true)->first();
    }
}