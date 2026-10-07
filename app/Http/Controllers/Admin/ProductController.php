<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\SousCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request){
        $query = Product::with(['category', 'sousCategory', 'variants']);
        if ($request->filled('category_id')) {
            $query->where(function($q) use ($request) {
                $q->where('category_id', $request->category_id)
                ->orWhere('sous_category_id', $request->category_id);
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('stock_filter')) {
            if ($request->stock_filter === 'in_stock') {
                $query->whereHas('variants', fn ($q) => $q->where('qte_dispo', '>', 0));
            } elseif ($request->stock_filter === 'out_of_stock') {
                $query->whereDoesntHave('variants', fn ($q) => $q->where('qte_dispo', '>', 0));
            }
        }
        if ($request->filled('price_min')) {
            $query->whereHas('variants', fn ($q) => $q->where('prix_vente', '>=', $request->price_min));
        }
        if ($request->filled('price_max')) {
            $query->whereHas('variants', fn ($q) => $q->where('prix_vente', '<=', $request->price_max));
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->search($search);
        }

        $sortField = in_array($request->get('sort'), ['created_at', 'designation', 'reference_prod'], true)
            ? $request->get('sort') : 'created_at';
        $sortDirection = $request->get('direction') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortField, $sortDirection);

        $products = $query->paginate(15)->withQueryString();
        $categories = Category::with('sousCategories')->orderBy('nom')->get();
        return view('admin.produits.index', compact('products', 'categories'));
    }

    public function create(){
        $categories = Category::orderBy('nom')->get();
        return view('admin.produits.create', compact('categories'));
    }

    public function store(Request $request){
        $data = $request->validate([
            'designation' => 'required|string|max:255',
            'description' => 'nullable|string',
            'stock_minimum' => 'nullable|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'sous_category_id' => 'nullable|exists:sous_categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status' => 'sometimes|in:draft,published,out_of_stock',
        ]);

        $data['qte_dispo']      = 0;
        $data['reference_prod'] = $this->generateUniqueReference();
        $data['slug']           = Product::generateUniqueSlug($data['designation']);

        if ($request->filled('sous_category_id')) {
            $sousCategory = SousCategory::find($request->sous_category_id);
            $data['category_id'] = $sousCategory->category_id;
            $data['sous_category_id'] = $request->sous_category_id;
        } else {
            $data['sous_category_id'] = null;
        }

       if ($request->hasFile('image')) {
            $data['image_path'] = $this->optimizeAndStoreImage(
                $request->file('image')
            );
        }

        Product::create($data);
        return redirect()->route('admin.produits.index')->with('success', 'Produit créé avec succès.');
    }

    //Optimiser les imaghesz en les convertissants au format Webp
    private function optimizeAndStoreImage($image, string $directory = 'products'): string{
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
        $maxSize = 1200;

        if ($width > $maxSize || $height > $maxSize) {
            $ratio = min($maxSize / $width, $maxSize / $height);
            $newWidth = (int) round($width * $ratio);
            $newHeight = (int) round($height * $ratio);
        } else {
            $newWidth = $width;
            $newHeight = $height;
        }
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

    private function generateUniqueReference(): string{
        do {
            $reference = 'REF-' . strtoupper(Str::random(8));
        } while (Product::where('reference_prod', $reference)->exists());

        return $reference;
    }

    public function show(string $id){
        $product = Product::with(['category', 'sousCategory', 'orderItems', 'achatProducts'])->findOrFail($id);
        $totalVendu = $product->orderItems->sum('qte');
        $stockAlerte = $product->qte_dispo <= 5 ? true : false;
        return view('admin.produits.show', compact('product', 'totalVendu', 'stockAlerte'));
    }

    public function edit(string $id){
        $product = Product::with(['category', 'sousCategory'])->findOrFail($id);
        $categories = Category::orderBy('nom')->get();
        return view('admin.produits.edit', compact('product', 'categories'));
    }

    public function update(Request $request, string $id){
        $product = Product::findOrFail($id);

        $data = $request->validate([
            'reference_prod' => ['required', 'string', 'max:50', Rule::unique('products')->ignore($product->id)],
            'designation' => 'required|string|max:255',
            'description' => 'nullable|string',
            'stock_minimum' => 'nullable|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'sous_category_id' => 'nullable|exists:sous_categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status' => 'sometimes|in:draft,published,out_of_stock',
        ]);

        if ($product->designation !== $data['designation']) {
            $data['slug'] = Product::generateUniqueSlug($data['designation'], $product->id);
        }

        if ($request->filled('sous_category_id')) {
            $sousCategory = SousCategory::find($request->sous_category_id);
            $data['category_id'] = $sousCategory->category_id;
            $data['sous_category_id'] = $request->sous_category_id;
        } else {
            $data['sous_category_id'] = null;
        }

       if ($request->hasFile('image')) {
            $newImagePath = $this->optimizeAndStoreImage(
                $request->file('image')
            );
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $data['image_path'] = $newImagePath;
        }
        $product->update($data);
        return redirect()->route('admin.produits.index')->with('success', 'Produit mis à jour avec succès.');
    }

    public function destroy(string $id){
        $product = Product::findOrFail($id);
        if ($product->orderItems()->exists()) {
            return redirect()->route('admin.produits.index')
                ->with('error', 'Impossible de supprimer ce produit car il a des commandes associées.');
        }
        if ($product->achatProducts()->exists()) {
            return redirect()->route('admin.produits.index')
                ->with('error', 'Impossible de supprimer ce produit car il est associé à un ou plusieurs achats.');
        }
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }
        $product->delete();
        return redirect()->route('admin.produits.index')->with('success', 'Produit supprimé avec succès.');
    }

    public function getSousCategories($categoryId){
        $category = Category::findOrFail($categoryId);
        $sousCategories = $category->sousCategories()->where('statut', 'actif')->orderBy('ordre')->orderBy('nom')->get(['id', 'nom']);
        return response()->json($sousCategories);
    }
}