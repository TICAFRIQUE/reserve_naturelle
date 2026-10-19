<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /* ------------------------------------------------------------------
     |  Helpers
     | ------------------------------------------------------------------ */

    /** Contrainte : variantes actives uniquement (pour whereHas). */
    private function activeOnly(): Closure{
        return fn ($q) => $q->where('actif', true);
    }

    /** Eager loading : variantes actives, de la moins chère à la plus chère. */
    private function activeVariants(): Closure{
        return fn ($q) => $q->where('actif', true)->orderBy('prix_vente');
    }

    /**
     * Catégories actives, ordonnées, avec le nombre de produits réellement
     * vendables (au moins une variante active). Retourne un builder.
     */
    private function activeCategoriesQuery(): Builder{
        return Category::query()
            ->where('statut', 'actif')
            ->orderBy('ordre')
            ->orderBy('nom')
            ->withCount([
                'products' => fn ($q) => $q->whereHas('variants', $this->activeOnly()),
            ]);
    }

    private function prixMinSubquery(): Builder{
        return ProductVariant::query()
            ->selectRaw('MIN(prix_vente)')
            ->whereColumn('product_variants.product_id', 'products.id')
            ->where('actif', true);
    }

    /* ------------------------------------------------------------------
     |  Pages
     | ------------------------------------------------------------------ */

    public function index(Request $request): View{
        $products = Product::with(['category', 'variants' => $this->activeVariants()])
            ->whereHas('variants', $this->activeOnly())
            ->latest()
            ->take(4)
            ->get();

        $categories = $this->activeCategoriesQuery()->get();
        return view('client.products.index', compact('products', 'categories'));
    }

    public function catalogue(Request $request): View{
        // Slider de catégories
        $categories = $this->activeCategoriesQuery()->get();

        // Catégories + 4 derniers produits vendables chacun
        // (limit() en eager loading s'applique par catégorie depuis Laravel 11)
        $categoriesWithProducts = $this->activeCategoriesQuery()
            ->with(['products' => fn ($q) => $q
                ->whereHas('variants', $this->activeOnly())
                ->with(['category', 'variants' => $this->activeVariants()])
                ->latest()
                ->limit(4),
            ])
            ->get();

        $recentProducts = Product::with(['variants' => $this->activeVariants()])
            ->whereHas('variants', $this->activeOnly())
            ->latest()
            ->limit(4)
            ->get();

        // Compteurs globaux
        $totalProducts   = Product::whereHas('variants', $this->activeOnly())->count();
        $totalCategories = $categories->count();
        $totalInStock    = Product::whereHas(
            'variants',
            fn ($q) => $q->where('actif', true)->where('qte_dispo', '>', 0)
        )->count();

        return view('client.products.catalogue', compact(
            'categories',
            'categoriesWithProducts',
            'recentProducts',
            'totalProducts',
            'totalCategories',
            'totalInStock'
        ));
    }

    public function show(Product $product): View{
        $product->load(['category', 'variants' => $this->activeVariants()]);
        $similarProducts = Product::with(['variants' => $this->activeVariants()])
            ->whereHas('variants', $this->activeOnly())
            ->where('category_id', $product->category_id)
            ->whereKeyNot($product->getKey())
            ->limit(4)
            ->get();

        return view('client.products.show', compact('product', 'similarProducts'));
    }

    /**
     * Produits d'une catégorie.
     * Route : client.products.category
     */
    public function category(Category $category): View{
        abort_if($category->statut !== 'actif', 404);

        $products = Product::with(['variants' => $this->activeVariants()])
            ->whereHas('variants', $this->activeOnly())
            ->where('category_id', $category->id)
            ->latest()
            ->get();

        return view('client.products.category', [
            'category'   => $category,
            'products'   => $products,
            'categories' => $this->activeCategoriesQuery()->get(),
        ]);
    }

    public function search(Request $request): JsonResponse{
        $validated = $request->validate([
            'q' => ['required', 'string', 'min:2', 'max:255'],
        ]);

        // Échappe % et _ pour que la saisie soit traitée littéralement
        $term = '%' . addcslashes($validated['q'], '%_\\') . '%';

        $products = Product::query()
            ->select('products.id', 'products.designation', 'products.slug', 'products.image_path')
            ->addSelect(['prix_vente' => $this->prixMinSubquery()])
            ->whereHas('variants', $this->activeOnly())
            ->where(function ($q) use ($term) {
                $q->where('designation', 'LIKE', $term)
                  ->orWhere('description', 'LIKE', $term);
            })
            ->orderBy('designation')
            ->limit(10)
            ->get();

        return response()->json($products);
    }
}