@extends('layouts.admin')

@section('title', 'Gestion des Commandes - La Réserve Naturelle')

@section('content')
<div class="container" style="padding: 40px 0;">
    <div class="row">
        <div class="col-12">
            <!-- En-tête avec titre et badge total -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h1 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 2rem; margin: 0;">
                        <i class="fas fa-shopping-cart" style="color: #2d5a27; margin-right: 10px;"></i>
                        Gestion des commandes
                    </h1>
                    <p style="color: #6c757d; margin: 5px 0 0 0;">Gérez les commandes de votre boutique</p>
                </div>
                <span style="
                    background: #2d5a27;
                    color: white;
                    padding: 10px 24px;
                    border-radius: 30px;
                    font-size: 1rem;
                    font-weight: 500;
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                ">
                    <i class="fas fa-shopping-bag"></i>
                    Total : {{ $orders->total() }}
                </span>
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

            <!-- Formulaire de filtre -->
            <div style="
                background: #f8f5f0;
                padding: 20px;
                border-radius: 12px;
                margin-bottom: 25px;
                border: 1px solid #e8e0d5;
            ">
                <form action="{{ route('admin.orders.index') }}" method="GET" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end;">
                    <div style="flex: 0 0 180px; min-width: 140px;">
                        <label for="statut" style="display: block; font-weight: 600; color: #2d5a27; font-size: 0.9rem; margin-bottom: 5px;">
                            <i class="fas fa-tag" style="color: #2d5a27; margin-right: 5px;"></i> Statut
                        </label>
                        <select name="statut" id="statut" style="
                            width: 100%;
                            padding: 10px 15px;
                            border: 1px solid #e8e0d5;
                            border-radius: 8px;
                            font-size: 0.95rem;
                            background: white;
                            transition: border-color 0.3s;
                            outline: none;
                            cursor: pointer;
                        " onfocus="this.style.borderColor='#2d5a27'" onblur="this.style.borderColor='#e8e0d5'">
                            <option value="">Tous les statuts</option>
                            <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>⏳ En Attente</option>
                            <option value="payee" {{ request('statut') == 'payee' ? 'selected' : '' }}>💳 Payée</option>
                            <option value="validee" {{ request('statut') == 'validee' ? 'selected' : '' }}>✅ Validée</option>
                            <option value="livree" {{ request('statut') == 'livree' ? 'selected' : '' }}>🚚 Livrée</option>
                            <option value="annulee" {{ request('statut') == 'annulee' ? 'selected' : '' }}>❌ Annulée</option>
                        </select>
                    </div>

                    <div style="flex: 0 0 170px; min-width: 140px;">
                        <label for="date_debut" style="display: block; font-weight: 600; color: #2d5a27; font-size: 0.9rem; margin-bottom: 5px;">
                            <i class="fas fa-calendar" style="color: #2d5a27; margin-right: 5px;"></i> Date début
                        </label>
                        <input type="date" 
                               name="date_debut" 
                               id="date_debut"
                               style="
                                   width: 100%;
                                   padding: 10px 15px;
                                   border: 1px solid #e8e0d5;
                                   border-radius: 8px;
                                   font-size: 0.95rem;
                                   transition: border-color 0.3s;
                                   outline: none;
                                   background: white;
                               "
                               onfocus="this.style.borderColor='#2d5a27'"
                               onblur="this.style.borderColor='#e8e0d5'"
                               value="{{ request('date_debut') }}">
                    </div>

                    <div style="flex: 0 0 170px; min-width: 140px;">
                        <label for="date_fin" style="display: block; font-weight: 600; color: #2d5a27; font-size: 0.9rem; margin-bottom: 5px;">
                            <i class="fas fa-calendar" style="color: #2d5a27; margin-right: 5px;"></i> Date fin
                        </label>
                        <input type="date" 
                               name="date_fin" 
                               id="date_fin"
                               style="
                                   width: 100%;
                                   padding: 10px 15px;
                                   border: 1px solid #e8e0d5;
                                   border-radius: 8px;
                                   font-size: 0.95rem;
                                   transition: border-color 0.3s;
                                   outline: none;
                                   background: white;
                               "
                               onfocus="this.style.borderColor='#2d5a27'"
                               onblur="this.style.borderColor='#e8e0d5'"
                               value="{{ request('date_fin') }}">
                    </div>
                    
                    <div style="flex: 0 0 auto; display: flex; gap: 10px;">
                        <button type="submit" style="
                            background: #2d5a27;
                            color: white;
                            padding: 10px 25px;
                            border-radius: 30px;
                            border: none;
                            font-weight: 500;
                            cursor: pointer;
                            transition: all 0.3s ease;
                            display: inline-flex;
                            align-items: center;
                            gap: 8px;
                        " onmouseover="this.style.background='#1e3d1a'" onmouseout="this.style.background='#2d5a27'">
                            <i class="fas fa-filter"></i> Filtrer
                        </button>
                        <a href="{{ route('admin.orders.index') }}" style="
                            background: #e8e0d5;
                            color: #2d5a27;
                            padding: 10px 25px;
                            border-radius: 30px;
                            text-decoration: none;
                            font-weight: 500;
                            transition: all 0.3s ease;
                            display: inline-flex;
                            align-items: center;
                            gap: 8px;
                        " onmouseover="this.style.background='#d4c9bb'" onmouseout="this.style.background='#e8e0d5'">
                            <i class="fas fa-undo"></i> Réinitialiser
                        </a>
                    </div>
                </form>
            </div>

            <!-- Tableau des commandes -->
            <div style="
                background: white;
                border-radius: 12px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                overflow: hidden;
                border: 1px solid #e8e0d5;
            ">
                <div style="overflow-x: auto;">
                    <table style="
                        width: 100%;
                        border-collapse: collapse;
                        font-size: 0.95rem;
                    ">
                        <thead style="
                            background: #f8f5f0;
                            border-bottom: 2px solid #e8e0d5;
                        ">
                            <tr>
                                {{-- <th style="padding: 15px 20px; text-align: left; font-weight: 600; color: #2d5a27; width: 4%;">#</th> --}}
                                <th style="padding: 15px 20px; text-align: left; font-weight: 600; color: #2d5a27; width: 12%;">N° Commande</th>
                                <th style="padding: 15px 20px; text-align: left; font-weight: 600; color: #2d5a27; width: 15%;">Client</th>
                                <th style="padding: 15px 20px; text-align: left; font-weight: 600; color: #2d5a27; width: 16%;">Livraison</th>
                                <th style="padding: 15px 20px; text-align: left; font-weight: 600; color: #2d5a27; width: 11%;">Date</th>
                                <th style="padding: 15px 20px; text-align: right; font-weight: 600; color: #2d5a27; width: 12%;">Montant</th>
                                <th style="padding: 15px 20px; text-align: center; font-weight: 600; color: #2d5a27; width: 11%;">Statut</th>
                                <th style="padding: 15px 20px; text-align: center; font-weight: 600; color: #2d5a27; width: 9%;">Articles</th>
                                <th style="padding: 15px 20px; text-align: center; font-weight: 600; color: #2d5a27; width: 10%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr style="border-bottom: 1px solid #f0ebe5; transition: background 0.2s;" onmouseover="this.style.background='#faf8f5'" onmouseout="this.style.background='transparent'">
                                    {{-- <td style="padding: 15px 20px; color: #6c757d; font-weight: 500;">{{ $order->id }}</td> --}}
                                    <td style="padding: 15px 20px;">
                                        <span style="
                                            background: #e8f5e9;
                                            color: #2d5a27;
                                            padding: 4px 12px;
                                            border-radius: 20px;
                                            font-size: 0.85rem;
                                            font-weight: 600;
                                            display: inline-block;
                                        ">
                                            <i class="fas fa-hashtag" style="font-size: 10px;"></i>
                                            {{ $order->num_order }}
                                        </span>
                                    </td>
                                    <td style="padding: 15px 20px;">
                                        <div style="font-weight: 500; color: #2d5a27;">{{ $order->user->prenom ?? $order->user->name ?? 'N/A' }}</div>
                                        <div style="color: #6c757d; font-size: 0.8rem;">
                                            <i class="fas fa-envelope" style="color: #2d5a27; font-size: 10px;"></i>
                                            {{ $order->user->email ?? '' }}
                                        </div>
                                    </td>
                                    <td style="padding: 15px 20px;">
                                        @if($order->adresse_precise)
                                            <div style="color: #2d5a27; font-size: 0.85rem;">
                                                <i class="fas fa-map-marker-alt" style="color: #b8860b; font-size: 10px;"></i>
                                                {{ Str::limit($order->adresse_precise, 40) }}
                                            </div>
                                            @if($order->zone)
                                                <div style="color: #6c757d; font-size: 0.75rem; margin-top: 2px;">
                                                    {{ $order->zone->nom }}
                                                    @if($order->ville_expedition) — {{ $order->ville_expedition }} @endif
                                                </div>
                                            @endif
                                        @else
                                            <span style="color: #adb5bd; font-size: 0.8rem;">Non renseignée</span>
                                        @endif
                                    </td>
                                    <td style="padding: 15px 20px;">
                                        <div style="color: #2d5a27; font-weight: 500;">
                                            @if($order->date_order instanceof \Carbon\Carbon)
                                                {{ $order->date_order->format('d/m/Y') }}
                                            @else
                                                {{ date('d/m/Y', strtotime($order->date_order)) }}
                                            @endif
                                        </div>
                                        <div style="color: #6c757d; font-size: 0.75rem;">
                                            @if($order->created_at instanceof \Carbon\Carbon)
                                                {{ $order->created_at->diffForHumans() }}
                                            @else
                                                {{ date('d/m/Y', strtotime($order->created_at)) }}
                                            @endif
                                        </div>
                                    </td>
                                    <td style="padding: 15px 20px; text-align: right;">
                                        <span style="font-weight: 700; color: #2d5a27; font-size: 1.05rem;">
                                            {{ number_format($order->mt_total ?? $order->total ?? 0, 0, ',', ' ') }} FCFA
                                        </span>
                                    </td>
                                    <td style="padding: 15px 20px; text-align: center;">
                                        @php
                                            $statusConfig = [
                                                'en_attente' => ['bg' => '#fff3cd', 'color' => '#856404', 'icon' => 'fa-clock'],
                                                'payee' => ['bg' => '#f3e5f5', 'color' => '#6a1b9a', 'icon' => 'fa-money-check-alt'],
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
                                            font-size: 0.8rem;
                                            font-weight: 500;
                                            display: inline-block;
                                        ">
                                            <i class="fas {{ $config['icon'] }}" style="margin-right: 5px;"></i>
                                            {{ ucfirst(str_replace('_', ' ', $order->statut)) }}
                                        </span>
                                    </td>
                                    <td style="padding: 15px 20px; text-align: center;">
                                        <span style="
                                            background: #f8f5f0;
                                            color: #2d5a27;
                                            padding: 4px 14px;
                                            border-radius: 20px;
                                            font-size: 0.85rem;
                                            font-weight: 600;
                                            display: inline-block;
                                        ">
                                            {{ $order->items->count() }}
                                        </span>
                                    </td>
                                    <td style="padding: 15px 20px; text-align: center; white-space: nowrap;">
                                        <a href="{{ route('admin.orders.show', $order) }}" style="
                                            background: #f0ebe5;
                                            color: #2d5a27;
                                            padding: 8px 14px;
                                            border-radius: 20px;
                                            text-decoration: none;
                                            font-size: 0.85rem;
                                            transition: all 0.2s;
                                            display: inline-flex;
                                            align-items: center;
                                            gap: 6px;
                                        " onmouseover="this.style.background='#e0d6c8'" onmouseout="this.style.background='#f0ebe5'">
                                            <i class="fas fa-eye"></i> Détails
                                        </a>

                                        @if($order->statut === 'en_attente')
                                            <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" style="display:inline-block; margin-left:6px;"
                                                  onsubmit="return confirm('Supprimer définitivement la commande {{ $order->num_order }} ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" style="
                                                    background: #f8d7da;
                                                    color: #721c24;
                                                    padding: 8px 14px;
                                                    border-radius: 20px;
                                                    border: none;
                                                    font-size: 0.85rem;
                                                    cursor: pointer;
                                                    transition: all 0.2s;
                                                    display: inline-flex;
                                                    align-items: center;
                                                    gap: 6px;
                                                " onmouseover="this.style.background='#f1b0b7'" onmouseout="this.style.background='#f8d7da'">
                                                    <i class="fas fa-trash"></i> Supprimer
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" style="padding: 60px 20px; text-align: center; color: #6c757d;">
                                        <i class="fas fa-inbox" style="font-size: 48px; display: block; margin-bottom: 15px; color: #d4c9bb;"></i>
                                        <p style="font-size: 1.1rem; margin: 0;">Aucune commande trouvée</p>
                                        <p style="margin-top: 5px;">Aucune commande ne correspond à vos critères de recherche</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div style="margin-top: 30px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                <div style="display: flex; justify-content: center;">
                    {{ $orders->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Personnalisation de la pagination pour correspondre au thème */
    .pagination {
        display: flex;
        list-style: none;
        gap: 5px;
        padding: 0;
        margin: 0;
    }
    .pagination li {
        display: inline-block;
    }
    .pagination li a, .pagination li span {
        display: inline-block;
        padding: 8px 16px;
        background: white;
        border: 1px solid #e8e0d5;
        border-radius: 6px;
        color: #2d5a27;
        text-decoration: none;
        transition: all 0.2s;
        font-size: 0.9rem;
    }
    .pagination li a:hover {
        background: #2d5a27;
        color: white;
        border-color: #2d5a27;
    }
    .pagination li.active span {
        background: #2d5a27;
        color: white;
        border-color: #2d5a27;
    }
    .pagination li.disabled span {
        color: #adb5bd;
        background: #f8f5f0;
        border-color: #e8e0d5;
    }
    
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
        .container > div > div:first-child > div:last-child span {
            width: 100%;
            justify-content: center;
        }
        form {
            flex-direction: column !important;
        }
        form > div {
            width: 100% !important;
            flex: 1 1 auto !important;
        }
        form > div:last-child {
            flex-direction: column !important;
        }
        form > div:last-child button,
        form > div:last-child a {
            width: 100%;
            justify-content: center;
        }
        .pagination li a, .pagination li span {
            padding: 6px 12px;
            font-size: 0.8rem;
        }
        table {
            font-size: 0.85rem !important;
        }
        th, td {
            padding: 10px 12px !important;
        }
        .container > div > div:last-child {
            padding: 15px !important;
        }
    }
</style>
@endpush