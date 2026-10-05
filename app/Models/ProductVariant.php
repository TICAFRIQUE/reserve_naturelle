<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'reference_prod',
        'conditionnement',
        'prix_vente',
        'qte_dispo',
        'stock_minimum',
        'cmp',
        'actif',
    ];

    protected $casts = [
        'prix_vente'    => 'integer',
        'qte_dispo'     => 'integer',
        'stock_minimum' => 'integer',
        'cmp'           => 'integer',
        'actif'         => 'boolean',
    ];

    // ============================================================
    // RELATIONS
    // ============================================================

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function stockMouvements(): HasMany
    {
        return $this->hasMany(StockMouvement::class, 'product_variant_id');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'product_variant_id');
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class, 'product_variant_id');
    }

    public function achatProducts(): HasMany
    {
        return $this->hasMany(AchatProduct::class, 'product_variant_id');
    }

    public function inventaireProducts(): HasMany
    {
        return $this->hasMany(InventaireProduct::class, 'product_variant_id');
    }

    // ============================================================
    // ACCESSORS
    // ============================================================

    public function getSousSeuilAttribute(): bool
    {
        return $this->qte_dispo <= $this->stock_minimum;
    }

    public function getLibelleAttribute(): string
    {
        $designation = $this->product?->designation ?? 'Produit inconnu';
        return "{$designation} ({$this->conditionnement})";
    }

    // ============================================================
    // SCOPES
    // ============================================================

    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    public function scopeSousSeuil($query)
    {
        return $query->whereColumn('qte_dispo', '<=', 'stock_minimum');
    }
}