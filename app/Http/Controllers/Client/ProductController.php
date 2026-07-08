<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Affiche la page d'accueil avec les 4 derniers produits
     * (PUBLIC)
     */
    public function index(Request $request)
    {
        // Récupérer les 4 derniers produits pour la page d'accueil
        $products = Product::with('category')
            ->latest() // Trie par created_at desc
            ->take(4)
            ->get();

        // Récupérer toutes les catégories pour le filtre
        $categories = Category::orderBy('nom')
            ->get();

        return view('client.products.index', compact('products', 'categories'));
    }

    /**
     * Affiche le catalogue complet de tous les produits
     * (PUBLIC - Page catalogue)
     */
    public function catalogue(Request $request)
    {
        $query = Product::with('category');

        // Recherche par désignation ou description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('designation', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // Filtre par catégorie
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filtre par prix
        if ($request->filled('prix_min')) {
            $query->where('prix_vente', '>=', $request->prix_min);
        }

        if ($request->filled('prix_max')) {
            $query->where('prix_vente', '<=', $request->prix_max);
        }

        // Tri
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('prix_vente', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('prix_vente', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'stock_desc':
                    $query->orderBy('qte_dispo', 'desc');
                    break;
                case 'stock_asc':
                    $query->orderBy('qte_dispo', 'asc');
                    break;
                default:
                    $query->orderBy('designation', 'asc');
                    break;
            }
        } else {
            $query->orderBy('designation', 'asc');
        }

        // Pagination (12 produits par page)
        $products = $query->paginate(12)->withQueryString();

        // Récupérer toutes les catégories pour le filtre
        $categories = Category::orderBy('nom')->get();

        // Statistiques
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalInStock = Product::where('qte_dispo', '>', 0)->count();

        return view('client.products.catalogue', compact(
            'products', 
            'categories',
            'totalProducts',
            'totalCategories',
            'totalInStock'
        ));
    }

    /**
     * Affiche le détail d'un produit
     * (PROTÉGÉ - Nécessite connexion)
     */
    public function show(Product $product)
    {
        $product->load('category');

        $similarProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        return view('client.products.show', compact('product', 'similarProducts'));
    }

    /**
     * Recherche rapide pour autocomplétion (AJAX)
     * (PROTÉGÉ - Nécessite connexion)
     */
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2|max:255',
        ]);

        $search = $request->get('q');

        $products = Product::where(function ($q) use ($search) {
                $q->where('designation', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            })
            ->orderBy('designation')
            ->limit(10)
            ->get(['id', 'designation', 'prix_vente', 'image_path']);

        return response()->json($products);
    }
}