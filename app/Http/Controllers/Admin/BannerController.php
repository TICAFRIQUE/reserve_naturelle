<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\BannerSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BannerController extends Controller
{
    /**
     * Afficher le formulaire d'édition
     */
    public function edit()
    {
        $banner = Banner::firstOrCreate([], [
            'titre' => 'Le meilleur de la',
            'titre_span' => 'terre ivoirienne',
            'sous_titre' => 'Des produits naturels sélectionnés directement auprès des producteurs locaux.',
            'texte_bouton' => 'Découvrir le catalogue',
            'lien_bouton' => '#catalogue',
            'actif' => true,
        ]);

        $slides = $banner->slides()->get();

        return view('admin.banners.edit', compact('banner', 'slides'));
    }

    /**
     * Upload multiple de slides (pour le slider)
     */
    public function storeSlides(Request $request)
    {
        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $banner = Banner::first();

        if ($request->hasFile('images')) {
            $ordre = $banner->slides()->max('ordre') + 1;

            foreach ($request->file('images') as $image) {
                $path = $this->optimizeAndStoreImage($image, 'banners');

                BannerSlide::create([
                    'banner_id' => $banner->id,
                    'image_path' => $path,
                    'actif' => true,
                    'ordre' => $ordre++,
                ]);
            }

            Cache::forget('banner.active');

            return redirect()
                ->route('admin.banner.edit')
                ->with('success', count($request->file('images')) . ' slide(s) ajouté(s)');
        }

        return redirect()
            ->route('admin.banner.edit')
            ->with('error', 'Aucune image détectée');
    }

    /**
     * Supprimer un slide
     */
    public function destroySlide(BannerSlide $slide)
    {
        if ($slide->image_path && Storage::disk('public')->exists($slide->image_path)) {
            Storage::disk('public')->delete($slide->image_path);
        }

        $slide->delete();

        Cache::forget('banner.active');

        return redirect()
            ->route('admin.banner.edit')
            ->with('success', 'Slide supprimé');
    }

    /**
     * Toggle actif/inactif un slide
     */
    public function toggleSlide(BannerSlide $slide)
    {
        $slide->update(['actif' => !$slide->actif]);
        Cache::forget('banner.active');

        return redirect()
            ->route('admin.banner.edit')
            ->with('success', 'Slide ' . ($slide->actif ? 'activé' : 'désactivé'));
    }

    /**
     * Optimiser et stocker une image
     */
    private function optimizeAndStoreImage($image, string $directory = 'banners'): string
    {
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
        $ratio = min($maxWidth / $width, $maxHeight / $height, 1);
        $newWidth = (int) round($width * $ratio);
        $newHeight = (int) round($height * $ratio);
        $optimized = imagecreatetruecolor($newWidth, $newHeight);
        imagealphablending($optimized, false);
        imagesavealpha($optimized, true);

        imagecopyresampled($optimized, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        $filename = Str::uuid() . '.webp';
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
     * Mettre à jour la bannière (textes + image principale)
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

        // Image principale (1er slide)
        if ($request->hasFile('image')) {
            if ($banner->image_path && Storage::disk('public')->exists($banner->image_path)) {
                Storage::disk('public')->delete($banner->image_path);
            }
            $validated['image_path'] = $this->optimizeAndStoreImage($request->file('image'));
        }

        $validated['actif'] = $request->has('actif');

        $banner->update($validated);

        Cache::forget('banner.active');

        return redirect()
            ->route('admin.banner.edit')
            ->with('success', 'Bannière mise à jour avec succès');
    }
}