@extends('layouts.admin')

@section('title', 'Détails de la Commande #' . $order->num_order . ' - La Réserve Naturelle')

@section('content')
<div class="container" style="padding: 40px 0;">
    <div class="row">
        <div class="col-12">
            <!-- En-tête avec titre et bouton retour -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h1 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 2rem; margin: 0;">
                        <i class="fas fa-file-invoice" style="color: #2d5a27; margin-right: 10px;"></i>
                        Commande #{{ $order->num_order }}
                    </h1>
                    <p style="color: #6c757d; margin: 5px 0 0 0;">
                        <i class="fas fa-calendar" style="color: #2d5a27; margin-right: 5px;"></i>
                        {{ $order->date_order->format('d/m/Y H:i') }} - 
                        <strong style="color: #2d5a27;">{{ $order->user->name ?? 'Client N/A' }}</strong>
                    </p>
                </div>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <a href="{{ route('admin.orders.index') }}" style="
                        background: #e8e0d5;
                        color: #2d5a27;
                        padding: 12px 24px;
                        border-radius: 30px;
                        text-decoration: none;
                        font-weight: 500;
                        display: inline-flex;
                        align-items: center;
                        gap: 8px;
                        transition: all 0.3s ease;
                    " onmouseover="this.style.background='#d4c9bb'" onmouseout="this.style.background='#e8e0d5'">
                        <i class="fas fa-arrow-left"></i> Retour à la liste
                    </a>
                </div>
            </div>

            <!-- Messages flash -->
            @if(session('success'))
                <div style="
                    background: #d4edda;
                    color: #155724;
                    padding: 12px 20px;
                    border-radius: 8px;
                    border-left: 4px solid #28a745;
                    margin-bottom: 20px;
                    display: flex;
                    align-items: center;
                    gap: 10px;
                ">
                    <i class="fas fa-check-circle" style="font-size: 20px;"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div style="
                    background: #f8d7da;
                    color: #721c24;
                    padding: 12px 20px;
                    border-radius: 8px;
                    border-left: 4px solid #dc3545;
                    margin-bottom: 20px;
                    display: flex;
                    align-items: center;
                    gap: 10px;
                ">
                    <i class="fas fa-exclamation-circle" style="font-size: 20px;"></i>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Informations de la commande -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px; margin-bottom: 30px;">
                <!-- Informations commande -->
                <div style="
                    background: white;
                    border-radius: 12px;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                    border: 1px solid #e8e0d5;
                    overflow: hidden;
                ">
                    <div style="
                        padding: 20px 25px;
                        border-bottom: 1px solid #e8e0d5;
                        background: #faf8f5;
                        display: flex;
                        align-items: center;
                        gap: 12px;
                    ">
                        <div style="
                            width: 40px;
                            height: 40px;
                            border-radius: 50%;
                            background: #2d5a27;
                            color: white;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                        ">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                        <h5 style="color: #2d5a27; margin: 0; font-size: 1.1rem;">
                            Informations de la commande
                        </h5>
                    </div>
                    <div style="padding: 20px 25px;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td style="padding: 8px 0; font-weight: 600; color: #2d5a27; width: 40%;">ID</td>
                                <td style="padding: 8px 0; color: #6c757d;">#{{ $order->id }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; font-weight: 600; color: #2d5a27;">Numéro de Commande</td>
                                <td style="padding: 8px 0;">
                                    <span style="
                                        background: #e8f5e9;
                                        color: #2d5a27;
                                        padding: 4px 12px;
                                        border-radius: 20px;
                                        font-size: 0.85rem;
                                        font-weight: 600;
                                        display: inline-block;
                                    ">
                                        <i class="fas fa-hashtag"></i> {{ $order->num_order }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; font-weight: 600; color: #2d5a27;">Date de Commande</td>
                                <td style="padding: 8px 0; color: #2d5a27; font-weight: 500;">
                                    {{ $order->date_order->format('d/m/Y H:i') }}
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; font-weight: 600; color: #2d5a27;">Montant Total</td>
                                <td style="padding: 8px 0;">
                                    <span style="font-size: 1.2rem; font-weight: 700; color: #2d5a27;">
                                        {{ number_format($order->mt_total, 0, ',', ' ') }} FCFA
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; font-weight: 600; color: #2d5a27;">Statut actuel</td>
                                <td style="padding: 8px 0;">
                                    @php
                                        $statusConfig = [
                                            'en_attente' => ['bg' => '#fff3cd', 'color' => '#856404', 'icon' => 'fa-clock'],
                                            'validee' => ['bg' => '#cce5ff', 'color' => '#004085', 'icon' => 'fa-check-circle'],
                                            'livree' => ['bg' => '#d4edda', 'color' => '#155724', 'icon' => 'fa-truck'],
                                            'annulee' => ['bg' => '#f8d7da', 'color' => '#721c24', 'icon' => 'fa-times-circle']
                                        ];
                                        $config = $statusConfig[$order->statut] ?? ['bg' => '#e8e0d5', 'color' => '#6c757d', 'icon' => 'fa-circle'];
                                    @endphp
                                    <span style="
                                        background: {{ $config['bg'] }};
                                        color: {{ $config['color'] }};
                                        padding: 6px 16px;
                                        border-radius: 20px;
                                        font-size: 0.9rem;
                                        font-weight: 500;
                                        display: inline-block;
                                    ">
                                        <i class="fas {{ $config['icon'] }}" style="margin-right: 5px;"></i>
                                        {{ ucfirst(str_replace('_', ' ', $order->statut)) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; font-weight: 600; color: #2d5a27;">Type de Remise</td>
                                <td style="padding: 8px 0;">
                                    @if($order->remise)
                                        <span style="
                                            background: #cce5ff;
                                            color: #004085;
                                            padding: 4px 14px;
                                            border-radius: 20px;
                                            font-size: 0.85rem;
                                            display: inline-block;
                                        ">
                                            {{ ucfirst($order->remise) }}
                                        </span>
                                    @else
                                        <span style="color: #6c757d;">Aucune</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; font-weight: 600; color: #2d5a27;">Créé le</td>
                                <td style="padding: 8px 0; color: #6c757d;">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; font-weight: 600; color: #2d5a27;">Modifié le</td>
                                <td style="padding: 8px 0; color: #6c757d;">{{ $order->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Informations client -->
                <div style="
                    background: white;
                    border-radius: 12px;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                    border: 1px solid #e8e0d5;
                    overflow: hidden;
                ">
                    <div style="
                        padding: 20px 25px;
                        border-bottom: 1px solid #e8e0d5;
                        background: #faf8f5;
                        display: flex;
                        align-items: center;
                        gap: 12px;
                    ">
                        <div style="
                            width: 40px;
                            height: 40px;
                            border-radius: 50%;
                            background: #b8860b;
                            color: white;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                        ">
                            <i class="fas fa-user"></i>
                        </div>
                        <h5 style="color: #2d5a27; margin: 0; font-size: 1.1rem;">
                            Informations Client
                        </h5>
                    </div>
                    <div style="padding: 20px 25px;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td style="padding: 8px 0; font-weight: 600; color: #2d5a27; width: 40%;">Client</td>
                                <td style="padding: 8px 0;">
                                    <span style="font-weight: 500; color: #2d5a27; font-size: 1.05rem;">
                                        {{ $order->user->name ?? 'N/A' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; font-weight: 600; color: #2d5a27;">
                                    <i class="fas fa-envelope" style="color: #2d5a27; margin-right: 5px;"></i> Email
                                </td>
                                <td style="padding: 8px 0;">
                                    <a href="mailto:{{ $order->user->email ?? '' }}" style="color: #2d5a27; text-decoration: none;">
                                        {{ $order->user->email ?? 'N/A' }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; font-weight: 600; color: #2d5a27;">
                                    <i class="fas fa-hashtag" style="color: #2d5a27; margin-right: 5px;"></i> ID Client
                                </td>
                                <td style="padding: 8px 0; color: #6c757d;">#{{ $order->user->id ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; font-weight: 600; color: #2d5a27;">
                                    <i class="fas fa-calendar-plus" style="color: #2d5a27; margin-right: 5px;"></i> Inscrit le
                                </td>
                                <td style="padding: 8px 0; color: #6c757d;">
                                    {{ $order->user->created_at->format('d/m/Y') ?? 'N/A' }}
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; font-weight: 600; color: #2d5a27;">
                                    <i class="fas fa-shopping-bag" style="color: #2d5a27; margin-right: 5px;"></i> Total commandes
                                </td>
                                <td style="padding: 8px 0;">
                                    <span style="
                                        background: #2d5a27;
                                        color: white;
                                        padding: 4px 14px;
                                        border-radius: 20px;
                                        font-size: 0.85rem;
                                        display: inline-block;
                                    ">
                                        {{ $order->user->orders->count() ?? 0 }} commande(s)
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Articles de la commande -->
            <div style="
                background: white;
                border-radius: 12px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                border: 1px solid #e8e0d5;
                overflow: hidden;
                margin-bottom: 30px;
            ">
                <div style="
                    padding: 20px 25px;
                    border-bottom: 1px solid #e8e0d5;
                    background: #faf8f5;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    flex-wrap: wrap;
                    gap: 10px;
                ">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="
                            width: 40px;
                            height: 40px;
                            border-radius: 50%;
                            background: #1565c0;
                            color: white;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                        ">
                            <i class="fas fa-boxes"></i>
                        </div>
                        <h5 style="color: #2d5a27; margin: 0; font-size: 1.1rem;">
                            Articles de la commande
                        </h5>
                    </div>
                    <span style="
                        background: #2d5a27;
                        color: white;
                        padding: 6px 16px;
                        border-radius: 20px;
                        font-size: 0.85rem;
                        font-weight: 500;
                    ">
                        {{ $order->items->count() }} article(s)
                    </span>
                </div>
                <div style="padding: 0; overflow-x: auto;">
                    @if($order->items->count() > 0)
                        <table style="width: 100%; border-collapse: collapse; font-size: 0.95rem;">
                            <thead style="background: #f8f5f0; border-bottom: 2px solid #e8e0d5;">
                                <tr>
                                    <th style="padding: 12px 20px; text-align: left; font-weight: 600; color: #2d5a27; width: 5%;">#</th>
                                    <th style="padding: 12px 20px; text-align: left; font-weight: 600; color: #2d5a27; width: 30%;">Produit</th>
                                    <th style="padding: 12px 20px; text-align: left; font-weight: 600; color: #2d5a27; width: 15%;">Référence</th>
                                    <th style="padding: 12px 20px; text-align: center; font-weight: 600; color: #2d5a27; width: 12%;">Quantité</th>
                                    <th style="padding: 12px 20px; text-align: right; font-weight: 600; color: #2d5a27; width: 18%;">Prix Unitaire</th>
                                    <th style="padding: 12px 20px; text-align: right; font-weight: 600; color: #2d5a27; width: 20%;">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $index => $item)
                                    <tr style="border-bottom: 1px solid #f0ebe5; transition: background 0.2s;" onmouseover="this.style.background='#faf8f5'" onmouseout="this.style.background='transparent'">
                                        <td style="padding: 12px 20px; color: #6c757d;">{{ $index + 1 }}</td>
                                        <td style="padding: 12px 20px;">
                                            <div style="display: flex; align-items: center; gap: 12px;">
                                                @if($item->product && $item->product->image_path)
                                                    <div style="
                                                        width: 45px;
                                                        height: 45px;
                                                        border-radius: 8px;
                                                        overflow: hidden;
                                                        flex-shrink: 0;
                                                        background: #f8f5f0;
                                                    ">
                                                        <img src="{{ asset('storage/' . $item->product->image_path) }}" 
                                                             alt="{{ $item->product->designation ?? '' }}"
                                                             style="width: 100%; height: 100%; object-fit: cover;">
                                                    </div>
                                                @else
                                                    <div style="
                                                        width: 45px;
                                                        height: 45px;
                                                        border-radius: 8px;
                                                        background: #f8f5f0;
                                                        display: flex;
                                                        align-items: center;
                                                        justify-content: center;
                                                        flex-shrink: 0;
                                                    ">
                                                        <i class="fas fa-box" style="color: #b8860b;"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div style="font-weight: 500; color: #2d5a27;">
                                                        {{ $item->product->designation ?? $item->product->name ?? 'Produit supprimé' }}
                                                    </div>
                                                    @if(!$item->product)
                                                        <span style="
                                                            background: #f8d7da;
                                                            color: #721c24;
                                                            padding: 2px 10px;
                                                            border-radius: 12px;
                                                            font-size: 0.7rem;
                                                            display: inline-block;
                                                            margin-top: 2px;
                                                        ">
                                                            <i class="fas fa-exclamation-triangle"></i> Produit non disponible
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td style="padding: 12px 20px; color: #6c757d;">
                                            {{ $item->product->reference_prod ?? $item->product->reference ?? 'N/A' }}
                                        </td>
                                        <td style="padding: 12px 20px; text-align: center;">
                                            <span style="
                                                background: #f8f5f0;
                                                color: #2d5a27;
                                                padding: 4px 12px;
                                                border-radius: 20px;
                                                font-size: 0.85rem;
                                                font-weight: 600;
                                                display: inline-block;
                                            ">
                                                {{ $item->quantity }}
                                            </span>
                                        </td>
                                        <td style="padding: 12px 20px; text-align: right; color: #6c757d;">
                                            {{ number_format($item->price, 0, ',', ' ') }} FCFA
                                        </td>
                                        <td style="padding: 12px 20px; text-align: right; font-weight: 700; color: #2d5a27;">
                                            {{ number_format($item->quantity * $item->price, 0, ',', ' ') }} FCFA
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot style="background: #f8f5f0; border-top: 2px solid #e8e0d5;">
                                <tr>
                                    <td colspan="5" style="padding: 15px 20px; text-align: right; font-size: 1.1rem; font-weight: 600; color: #2d5a27;">
                                        Total Général
                                    </td>
                                    <td style="padding: 15px 20px; text-align: right; font-size: 1.2rem; font-weight: 700; color: #2d5a27;">
                                        {{ number_format($order->mt_total, 0, ',', ' ') }} FCFA
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    @else
                        <div style="padding: 40px 20px; text-align: center; color: #6c757d;">
                            <i class="fas fa-box-open" style="font-size: 36px; display: block; margin-bottom: 12px; color: #d4c9bb;"></i>
                            <p style="font-size: 1.05rem; margin: 0;">Aucun article dans cette commande</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Changement de statut -->
            <div style="
                background: white;
                border-radius: 12px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                border: 1px solid #e8e0d5;
                overflow: hidden;
            ">
                <div style="
                    padding: 20px 25px;
                    border-bottom: 1px solid #e8e0d5;
                    background: #faf8f5;
                    display: flex;
                    align-items: center;
                    gap: 12px;
                ">
                    <div style="
                        width: 40px;
                        height: 40px;
                        border-radius: 50%;
                        background: #ffc107;
                        color: #2d5a27;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    ">
                        <i class="fas fa-sync-alt"></i>
                    </div>
                    <h5 style="color: #2d5a27; margin: 0; font-size: 1.1rem;">
                        Mettre à jour le statut
                    </h5>
                </div>
                <div style="padding: 20px 25px;">
                    <form action="{{ route('admin.orders.status', $order) }}" method="POST" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end;">
                        @csrf
                        @method('PATCH')
                        
                        <div style="flex: 1; min-width: 200px;">
                            <label for="statut" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 8px; font-size: 0.95rem;">
                                <i class="fas fa-tag" style="color: #2d5a27; margin-right: 5px;"></i>
                                Nouveau statut
                            </label>
                            <select name="statut" id="statut" style="
                                width: 100%;
                                padding: 12px 16px;
                                border: 2px solid #e8e0d5;
                                border-radius: 8px;
                                font-size: 1rem;
                                transition: border-color 0.3s;
                                outline: none;
                                background: #faf8f5;
                                cursor: pointer;
                            " onfocus="this.style.borderColor='#2d5a27'" onblur="this.style.borderColor='#e8e0d5'">
                                <option value="en_attente" {{ $order->statut == 'en_attente' ? 'selected' : '' }}>
                                    ⏳ En Attente
                                </option>
                                <option value="validee" {{ $order->statut == 'validee' ? 'selected' : '' }}>
                                    ✅ Validée
                                </option>
                                <option value="livree" {{ $order->statut == 'livree' ? 'selected' : '' }}>
                                    🚚 Livrée
                                </option>
                                <option value="annulee" {{ $order->statut == 'annulee' ? 'selected' : '' }}>
                                    ❌ Annulée
                                </option>
                            </select>
                        </div>
                        
                        <div style="flex: 0 0 auto;">
                            <button type="submit" style="
                                background: #2d5a27;
                                color: white;
                                padding: 12px 35px;
                                border-radius: 30px;
                                border: none;
                                font-weight: 500;
                                cursor: pointer;
                                transition: all 0.3s ease;
                                font-size: 1rem;
                                display: inline-flex;
                                align-items: center;
                                gap: 8px;
                            " onmouseover="this.style.background='#1e3d1a'" onmouseout="this.style.background='#2d5a27'">
                                <i class="fas fa-sync"></i> Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Responsive */
    @media (max-width: 768px) {
        .container {
            padding: 20px 15px !important;
        }
        .container > div > div:first-child {
            flex-direction: column !important;
            align-items: flex-start !important;
        }
        .container > div > div:first-child > div:last-child {
            width: 100%;
        }
        .container > div > div:first-child > div:last-child a {
            width: 100%;
            justify-content: center;
        }
        .container > div > div:nth-child(3) {
            grid-template-columns: 1fr !important;
        }
        .container > div > div:last-child > div:last-child > form {
            flex-direction: column !important;
        }
        .container > div > div:last-child > div:last-child > form > div:last-child {
            width: 100%;
        }
        .container > div > div:last-child > div:last-child > form > div:last-child button {
            width: 100%;
            justify-content: center;
        }
        table {
            font-size: 0.85rem !important;
        }
        th, td {
            padding: 8px 12px !important;
        }
        .container > div > div:nth-child(3) > div:first-child > div:last-child table td,
        .container > div > div:nth-child(3) > div:last-child > div:last-child table td {
            padding: 6px 0 !important;
        }
        .container > div > div:nth-child(4) > div:last-child > table tbody tr td:first-child {
            display: flex !important;
            flex-direction: column !important;
            align-items: flex-start !important;
        }
        .container > div > div:nth-child(4) > div:last-child > table tbody tr td:first-child > div {
            flex-wrap: wrap !important;
        }
    }
</style>
@endpush