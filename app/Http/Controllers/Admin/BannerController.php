<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    /**
     * Afficher le formulaire d'édition
     */
    public function edit()
    {
        // Crée une bannière par défaut si aucune n'existe
        $banner = Banner::firstOrCreate([], [
            'titre' => 'Le meilleur de la',
            'titre_span' => 'terre ivoirienne',
            'sous_titre' => 'Des produits naturels sélectionnés directement auprès des producteurs locaux.',
            'texte_bouton' => 'Découvrir le catalogue',
            'lien_bouton' => '#catalogue',
            'actif' => true,
        ]);

        return view('admin.banners.edit', compact('banner'));
    }

    /**
     * Mettre à jour la bannière
     */
    public function update(Request $request)
    {
        $banner = Banner::first();

        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'titre_span' => 'nullable|string|max:255',
            'sous_titre' => 'nullable|string',
            'texte_bouton' => 'nullable|string|max:100',
            'lien_bouton' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:5120',
        ]);

        // Gestion de l'image
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($banner->image_path && Storage::disk('public')->exists($banner->image_path)) {
                Storage::disk('public')->delete($banner->image_path);
            }

            $validated['image_path'] = $request->file('image')->store('banners', 'public');
        }

        // Case à cocher "actif"
        $validated['actif'] = $request->has('actif');

        $banner->update($validated);

        // Vider le cache
        Cache::forget('banner.active');

        return redirect()
            ->route('admin.banner.edit')
            ->with('success', 'Bannière mise à jour avec succès');
    }
}