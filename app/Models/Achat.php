<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Achat extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero',
        'fournisseur_id',
        'user_id',
        'statut',
        'date_achat',
        'date_reception_prevue',
        'date_reception',
        'mt_total',
        'mt_paye',
        'date_paiement',
        'notes',

    ];

    protected $casts = [
        'date_achat'             => 'date',
        'date_reception_prevue'  => 'date',
        'date_reception'         => 'date',
        'date_paiement'          => 'date',
    ];

    public function fournisseur(){
        return $this->belongsTo(Fournisseur::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function produits(){
        return $this->hasMany(AchatProduct::class);
    }

    public function stockMouvements(){
        return $this->morphMany(StockMouvement::class, 'source');
    }

    public function retoursFournisseur(){
        return $this->hasMany(RetourFournisseur::class);
    }

    // Vérifie si toutes les lignes sont totalement reçues
    public function estTotalementRecu(): bool{
        return $this->produits->every(
            fn($l) => $l->qte_recue >= $l->qte_commandee
        );
    }

    // Vérifie si au moins une ligne est partiellement reçue
    public function estPartiellementRecu(): bool{
        return $this->produits->some(
            fn($l) => $l->qte_recue > 0 && $l->qte_recue < $l->qte_commandee
        );
    }
    //afficher la quantité reçue sur le total
    public function getReceptionAttribute(): array{
        $commandee = $this->produits->sum('qte_commandee');
        $recue = $this->produits->sum('qte_recue');

        return [
            'recue' => $recue,
            'commandee' => $commandee,
            'couleur' => match(true) {
                $commandee > 0 && $recue >= $commandee => 'vert',
                $recue > 0 => 'orange',
                default => 'gris',
            },
        ];
    }
}