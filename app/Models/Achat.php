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

    // //Calculer le montant du apres paiement partiel
    // public function getResteAPayerAttribute():int{
    //     return max(0, $this->mt_total - $this->mt_paye);
    // }

    // //Afficher le statut du paiement
    // public function getStatutPaiementAttribute():string{
    //         if ($this->mt_paye <= 0) {
    //         return 'non_paye';
    //     }

    //     if ($this->mt_paye < $this->mt_total) {
    //         return 'partiellement_paye';
    //     }

    //     return 'paye';
    // }

    // //Enregistrer paiement
    // public function enregistrerPaiement(int $montant):void{
    //         if ($montant <= 0) {
    //         throw new \InvalidArgumentException(
    //             'Le montant du paiement doit être supérieur à zéro.'
    //         );
    //     }

    //     if ($montant > $this->reste_a_payer) {
    //         throw new \InvalidArgumentException(
    //             'Le montant dépasse le reste à payer.'
    //         );
    //     }

    //     $this->mt_paye += $montant;
    //     $this->date_paiement = now()->toDateString();

    //     $this->save();
    // }
}