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

    /**
     * Récupérer la bannière active (une seule)
     */
    public static function active(){
        return self::where('actif', true)->first();
    }

    /**
     * Relation : une bannière a plusieurs slides supplémentaires
     */
    public function slides(){
        return $this->hasMany(BannerSlide::class)->orderBy('ordre')->orderBy('id');
    }

    /**
     * Slides supplémentaires actifs uniquement
     */
    public function activeSlides(){
        return $this->hasMany(BannerSlide::class)
            ->where('actif', true)
            ->orderBy('ordre')
            ->orderBy('id');
    }

    /**
     * ⭐ Récupère TOUTES les images du slider :
     * - Image principale (banners.image_path) en 1er
     * - Puis les slides supplémentaires actifs
     *
     * @return array Liste d'URLs d'images
     */
    public function allSlideImages(): array{
        $images = [];

        // 1. Image principale (toujours en 1er)
        if ($this->image_path) {
            $images[] = asset('storage/' . $this->image_path);
        }

        // 2. Slides supplémentaires actifs
        foreach ($this->activeSlides as $slide) {
            if ($slide->image_path) {
                $images[] = $slide->image_url;
            }
        }
        return $images;
    }
}