<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'statut',
        'slug',
        'ordre',
        'description',
        'image_path',
    ];

    public function sousCategories(){
        return $this->hasMany(SousCategory::class);
    }

    public function products(){
        return $this->hasMany(Product::class);
    }

    /**
     * URL complète de l'image
     */
    public function getImageUrlAttribute(): ?string{
        return $this->image_path
            ? asset('storage/' . $this->image_path)
            : null;
    }

    /**
     * Scope : catégories actives
     */
    public function scopeActive($query){
        return $query->where('statut', 'actif');
    }

    /**
     * Scope : triées par ordre
     */
    public function scopeOrdered($query){
        return $query->orderBy('ordre')->orderBy('nom');
    }
        /**
     * Boot : auto-génère le slug si vide
     */
    protected static function booted(): void{
        static::saving(function (Category $category) {
            if (empty($category->slug)) {
                $category->slug = \Illuminate\Support\Str::slug($category->nom);
            }
        });
    }
    /**
     * Route binding par slug
     */
    public function getRouteKeyName(): string{
        return 'slug';
    }
}