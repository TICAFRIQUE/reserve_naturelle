<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class SitemapController extends Controller
{
    public function index()
    {
        $products   = Product::with('variants')->latest('updated_at')->get();
        $categories = Category::latest('updated_at')->get();

        return response()
            ->view('sitemap', compact('products', 'categories'))
            ->header('Content-Type', 'application/xml');
    }
}