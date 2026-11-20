<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    /**
     * Les attributs qui peuvent être assignés en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'num_reservation',
        'user_id',
        'product_id',
        'product_variant_id',
        'qte',
        'prix_unitaire',
        'montant',
        'statut',
        'note',
        'date_reservation',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_reservation' => 'date',
    ];

    /* -------------------------------------------------------------------------- */
    /*                                 Relations                                  */
    /* -------------------------------------------------------------------------- */

    /**
     * L'utilisateur qui a effectué la réservation.
     */
    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }

    /**
     * Le produit réservé.
     */
    public function product(): BelongsTo{
        return $this->belongsTo(Product::class);
    }

    /**
     * La variante spécifique du produit réservé (si applicable).
     */
    public function variant(): BelongsTo{
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}