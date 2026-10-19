<?php

namespace App\Providers;

use App\Models\Banner;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            // ⚠️ On cache un TABLEAU, pas un objet Eloquent
            $bannerData = Cache::remember('banner.active', 3600, function () {
                $banner = Banner::where('actif', true)
                    ->with(['activeSlides' => function ($q) {
                        $q->where('actif', true)->orderBy('ordre')->orderBy('id');
                    }])
                    ->first();

                if (!$banner) {
                    return null;
                }

                return [
                    'titre'        => $banner->titre,
                    'titre_span'   => $banner->titre_span,
                    'sous_titre'   => $banner->sous_titre,
                    'texte_bouton' => $banner->texte_bouton,
                    'lien_bouton'  => $banner->lien_bouton,
                    'image_path'   => $banner->image_path,
                    'slides'       => $banner->activeSlides->map(function ($s) {
                        return [
                            'image_path' => $s->image_path,
                        ];
                    })->toArray(),
                ];
            });

            // Construit la liste des images
            $sliderImages = [];

            if ($bannerData) {
                if (!empty($bannerData['image_path'])) {
                    $sliderImages[] = asset('storage/' . $bannerData['image_path']);
                }

                foreach ($bannerData['slides'] ?? [] as $slide) {
                    if (!empty($slide['image_path'])) {
                        $sliderImages[] = asset('storage/' . $slide['image_path']);
                    }
                }
            }

            // Objet pour les textes (compat avec ton layout)
            $view->with('bannerGlobal', $bannerData ? (object) $bannerData : null);
            $view->with('sliderImages', $sliderImages);
        });
    }
}