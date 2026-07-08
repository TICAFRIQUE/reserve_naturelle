@extends('layouts.app')

@section('title', 'Mon Panier')

@section('content')
<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 40px 20px;">
    <h1 style="font-size: 32px; margin-bottom: 30px;">🛒 Mon Panier</h1>

    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('error') }}
        </div>
    @endif

    @if($cart->items->count() > 0)
        <div style="background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background: #f8f9fa;">
                    <tr>
                        <th style="padding: 15px; text-align: left; font-weight: 600;">Produit</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600;">Prix</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600;">Quantité</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600;">Total</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cart->items as $item)
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 15px; display: flex; align-items: center; gap: 15px;">
                                <img src="{{ $item->product->image_path ? asset('storage/' . $item->product->image_path) : asset('images/default-product.jpg') }}"
                                     style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                <div>
                                    <strong>{{ $item->product->designation }}</strong>
                                    <p style="font-size: 12px; color: #6c757d; margin: 0;">
                                        {{ $item->product->category->nom ?? 'Non catégorisé' }}
                                    </p>
                                </div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                {{ number_format($item->product->prix_vente, 0, ',', ' ') }} FCFA
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <form action="{{ route('client.cart.update', $item->id) }}" method="POST" style="display: flex; justify-content: center; gap: 5px; align-items: center;">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="qte" value="{{ $item->qte }}" min="1" max="{{ $item->product->qte_dispo }}"
                                           style="width: 60px; padding: 5px; border: 2px solid #e0e0e0; border-radius: 6px; text-align: center;">
                                    <button type="submit" style="background: #2e7d32; color: white; padding: 5px 10px; border: none; border-radius: 6px; cursor: pointer;">✓</button>
                                </form>
                            </td>
                            <td style="padding: 15px; text-align: center; font-weight: 700; color: #1b5e20;">
                                {{ number_format($item->qte * $item->product->prix_vente, 0, ',', ' ') }} FCFA
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <form action="{{ route('client.cart.destroy', $item->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Supprimer cet article ?')"
                                            style="background: #dc3545; color: white; padding: 5px 12px; border: none; border-radius: 6px; cursor: pointer;">✕</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="padding: 20px; background: #f8f9fa; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <strong style="font-size: 18px;">Total :</strong>
                    <span style="font-size: 24px; font-weight: 700; color: #1b5e20; margin-left: 15px;">
                        {{ number_format($total, 0, ',', ' ') }} FCFA
                    </span>
                </div>
                <div style="display: flex; gap: 15px;">
                    <form action="{{ route('client.cart.clear') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Vider le panier ?')"
                                style="background: #dc3545; color: white; padding: 10px 25px; border: none; border-radius: 8px; cursor: pointer;">
                            🗑️ Vider
                        </button>
                    </form>
                    <button style="background: #2e7d32; color: white; padding: 10px 30px; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">
                        📦 Passer commande
                    </button>
                </div>
            </div>
        </div>
    @else
        <div style="text-align: center; padding: 80px 20px; background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
            <div style="font-size: 64px; margin-bottom: 20px;">🛒</div>
            <h3 style="color: #333; font-size: 24px; margin-bottom: 10px;">Votre panier est vide</h3>
            <p style="color: #6c757d; margin-bottom: 20px;">Commencez vos achats dès maintenant !</p>
            <a href="{{ route('client.products.catalogue') }}" style="background: #2e7d32; color: white; padding: 12px 30px; border-radius: 50px; text-decoration: none; display: inline-block;">
                Voir les produits
            </a>
        </div>
    @endif
</div>
@endsection