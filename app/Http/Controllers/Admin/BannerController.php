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

    //Optimiser les images 
    private function optimizeAndStoreImage($image, string $directory = 'banners'): string{
        $sourcePath = $image->getRealPath();
        $mime = $image->getMimeType();

        switch ($mime) {
            case 'image/jpeg':
                $source = imagecreatefromjpeg($sourcePath);
                break;

            case 'image/png':
                $source = imagecreatefrompng($sourcePath);
                imagepalettetotruecolor($source);
                imagealphablending($source, true);
                imagesavealpha($source, true);
                break;

            case 'image/webp':
                $source = imagecreatefromwebp($sourcePath);
                break;

            case 'image/gif':
                $source = imagecreatefromgif($sourcePath);
                break;

            default:
                throw new \InvalidArgumentException('Format d’image non pris en charge.');
        }

        $width = imagesx($source);
        $height = imagesy($source);
        $maxWidth = 1920;
        $maxHeight = 800;
        $ratio = min(
            $maxWidth / $width,
            $maxHeight / $height,
            1
        );
        $newWidth = (int) round($width * $ratio);
        $newHeight = (int) round($height * $ratio);
        $optimized = imagecreatetruecolor($newWidth, $newHeight);
        imagealphablending($optimized, false);
        imagesavealpha($optimized, true);

        imagecopyresampled(
            $optimized,
            $source,
            0,
            0,
            0,
            0,
            $newWidth,
            $newHeight,
            $width,
            $height
        );
        $filename = \Illuminate\Support\Str::uuid() . '.webp';
        $path = $directory . '/' . $filename;
        ob_start();
        imagewebp($optimized, null, 80);
        $imageContent = ob_get_clean();

        Storage::disk('public')->put($path, $imageContent);

        imagedestroy($source);
        imagedestroy($optimized);
        return $path;
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        // Gestion de l'image
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($banner->image_path && Storage::disk('public')->exists($banner->image_path)) {
                Storage::disk('public')->delete($banner->image_path);
            }
                $validated['image_path'] = $this->optimizeAndStoreImage(
                    $request->file('image')
                );
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