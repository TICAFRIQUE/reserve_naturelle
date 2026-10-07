<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private function prixMinSubquery(): Builder
    {
        return ProductVariant::query()
            ->selectRaw('MIN(prix_vente)')
            ->whereColumn('product_variants.product_id', 'products.id')
            ->where('actif', true);
    }

    private function stockTotalSubquery(): Builder
    {
        return ProductVariant::query()
            ->selectRaw('COALESCE(SUM(qte_dispo), 0)')
            ->whereColumn('product_variants.product_id', 'products.id')
            ->where('actif', true);
    }

    public function index(Request $request)
    {
        $products = Product::with(['category', 'variants'])
            ->whereHas('variants')
            ->latest()
            ->take(4)
            ->get();

        $categories = Category::orderBy('nom')->get();

        return view('client.products.index', compact('products', 'categories'));
    }

    public function catalogue(Request $request)
    {
        $query = Product::query()
            ->select('products.*')
            ->addSelect([
                'prix_min'    => $this->prixMinSubquery(),
                'stock_total' => $this->stockTotalSubquery(),
            ])
            ->with(['category', 'variants'])
            ->whereHas('variants');

        $search = $request->query('search');
        if (is_string($search) && trim($search) !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('designation', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->integer('category'));
        }

        $prixMin = is_numeric($request->query('prix_min')) ? max(0, (float) $request->query('prix_min')) : null;
        $prixMax = is_numeric($request->query('prix_max')) ? max(0, (float) $request->query('prix_max')) : null;
        if ($prixMin !== null || $prixMax !== null) {
            $query->whereHas('variants', function ($q) use ($prixMin, $prixMax) {
                if ($prixMin !== null) {
                    $q->where('prix_vente', '>=', $prixMin);
                }
                if ($prixMax !== null) {
                    $q->where('prix_vente', '<=', $prixMax);
                }
            });
        }

        $disponible = $request->query('disponible');
        if ($disponible === 'oui') {
            $query->whereHas('variants', fn ($q) => $q->where('qte_dispo', '>', 0));
        } elseif ($disponible === 'non') {
            $query->whereDoesntHave('variants', fn ($q) => $q->where('qte_dispo', '>', 0));
        }

        match ($request->query('sort')) {
            'price_asc'  => $query->orderBy('prix_min', 'asc'),
            'price_desc' => $query->orderBy('prix_min', 'desc'),
            'newest'     => $query->orderBy('products.created_at', 'desc'),
            'oldest'     => $query->orderBy('products.created_at', 'asc'),
            'stock_desc' => $query->orderBy('stock_total', 'desc'),
            'stock_asc'  => $query->orderBy('stock_total', 'asc'),
            default      => $query->orderBy('designation', 'asc'),
        };
        $query->orderBy('products.id');

        $products = $query->paginate(12)->withQueryString();

        $categories = Category::orderBy('nom')->get();

        $totalProducts   = Product::whereHas('variants')->count();
        $totalCategories = Category::count();
        $totalInStock    = Product::whereHas('variants', fn ($q) => $q->where('qte_dispo', '>', 0))->count();

        return view('client.products.catalogue', compact(
            'products',
            'categories',
            'totalProducts',
            'totalCategories',
            'totalInStock'
        ));
    }

    public function show(Product $product)
    {
        $product->load(['category', 'variants']);

        $similarProducts = Product::with('variants')
            ->whereHas('variants')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        return view('client.products.show', compact('product', 'similarProducts'));
    }

    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2|max:255',
        ]);

        $search = $request->get('q');

        $products = Product::query()
            ->select('products.id', 'products.designation', 'products.slug', 'products.image_path')
            ->addSelect(['prix_vente' => $this->prixMinSubquery()])
            ->whereHas('variants')
            ->where(function ($q) use ($search) {
                $q->where('designation', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            })
            ->orderBy('designation')
            ->limit(10)
            ->get();

        return response()->json($products);
    }
}