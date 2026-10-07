<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    {{-- Accueil --}}
    <url>
        <loc>{{ route('home') }}</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>

    {{-- Catalogue --}}
    <url>
        <loc>{{ route('client.products.catalogue') }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>

    {{-- Contact --}}
    <url>
        <loc>{{ route('contact') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>

    {{-- Catégories --}}
    @foreach($categories as $category)
        <url>
            <loc>{{ route('client.products.catalogue', ['category' => $category->id]) }}</loc>
            <lastmod>{{ $category->updated_at?->toAtomString() ?? now()->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.7</priority>
        </url>
    @endforeach

    {{-- Produits --}}
    @foreach($products as $product)
        <url>
            <loc>{{ route('client.products.show', $product) }}</loc>
            <lastmod>{{ $product->updated_at?->toAtomString() ?? now()->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
        </url>
    @endforeach

</urlset>