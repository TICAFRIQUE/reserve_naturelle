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
        ->view('sitemap.index', compact('urls'))
        ->header('Content-Type', 'application/xml; charset=utf-8')
        ->header('Cache-Control', 'public, max-age=3600');
    }
}