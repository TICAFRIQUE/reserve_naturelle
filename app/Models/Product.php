<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_prod',
        'designation',
        'slug',
        'description',
        'stock_minimum',
        'cmp',
        'image_path',
        'category_id',
        'sous_category_id',
    ];

    protected static function booted(): void
    {
        static::creating(function ($product) {
            if (empty($product->slug) && !empty($product->designation)) {
                $product->slug = static::generateUniqueSlug($product->designation);
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('designation') && !$product->isDirty('slug')) {
                $product->slug = static::generateUniqueSlug($product->designation, $product->id);
            }
        });
    }

    public static function generateUniqueSlug(string $designation, ?int $ignoreId = null): string
    {
        $base = Str::slug($designation);
        $slug = $base;
        $i    = 1;

        while (static::where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }

    // ============================================================
    // RELATIONS
    // ============================================================

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function sousCategory(){
        return $this->belongsTo(SousCategory::class);
    }

    public function cartItems(){
        return $this->hasMany(CartItem::class);
    }

    public function orderItems(){
        return $this->hasMany(OrderItem::class);
    }

    public function orders(){
        return $this->belongsToMany(Order::class, 'order_items');
    }

    public function achatProducts(){
        return $this->hasMany(AchatProduct::class);
    }

    public function inventaireProducts(){
        return $this->hasMany(InventaireProduct::class);
    }

    public function stockMouvements(){
        return $this->hasMany(StockMouvement::class);
    }

    public function getSousSeuilAttribute(): bool{
        return $this->qte_dispo <= $this->stock_minimum;
    }

    public function scopeSearch($query, string $term){
        return $query->where(function ($q) use ($term) {
            $q->where('designation', 'like', "%{$term}%")
              ->orWhere('reference_prod', 'like', "%{$term}%")
              ->orWhere('slug', 'like', "%{$term}%");
        });
    }

    public function variants(){
        return $this->hasMany(ProductVariant::class)->where('actif', true)->orderBy('prix_vente');
    }
}