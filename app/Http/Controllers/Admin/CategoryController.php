<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(){
        $categories = Category::orderBy('ordre')->orderBy('nom')->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function create(){
        return view('admin.categories.create');
    }

    public function store(Request $request){
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'statut' => 'required|string',
            'description' => 'nullable|string',
            'ordre' => 'nullable|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        if ($request->hasFile('image')) {
            $data['image_path'] = $this->optimizeAndStoreImage($request->file('image'));
        }
        unset($data['image']);

        Category::create($data);
        return redirect()->route('admin.categories.index')->with('success', 'Catégorie créée avec succès.');
    }

    public function show(string $id){
        $category = Category::with(['sousCategories', 'products'])->findOrFail($id);
        return view('admin.categories.show', compact('category'));
    }

    public function edit(string $id){
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, string $id){
        $category = Category::findOrFail($id);
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'statut' => 'required|string',
            'description' => 'nullable|string',
            'ordre' => 'nullable|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image
            if ($category->image_path && Storage::disk('public')->exists($category->image_path)) {
                Storage::disk('public')->delete($category->image_path);
            }
            $data['image_path'] = $this->optimizeAndStoreImage($request->file('image'));
        }
        unset($data['image']);

        $category->update($data);
        return redirect()->route('admin.categories.index')->with('success', 'Catégorie mise à jour avec succès.');
    }

    public function destroy(string $id){
        $category = Category::findOrFail($id);

        if ($category->products()->exists() || $category->sousCategories()->exists()) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Impossible de supprimer cette catégorie car elle contient des produits ou sous-catégories.');
        }

        // Supprimer l'image
        if ($category->image_path && Storage::disk('public')->exists($category->image_path)) {
            Storage::disk('public')->delete($category->image_path);
        }
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Catégorie supprimée avec succès.');
    }

    /**
     * Optimise et stocke une image
     */
    private function optimizeAndStoreImage($image, string $directory = 'categories'): string{
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
        $maxWidth = 600;
        $maxHeight = 600;
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
}