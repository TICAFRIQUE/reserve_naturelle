@extends('layouts.admin')

@section('title', 'Tournée #' . $tournee->id . ' - La Réserve Naturelle')

@section('content')
<div class="container" style="padding: 40px 0;">
    <div class="row">
        <div class="col-12">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h1 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 2rem; margin: 0;">
                        <i class="fas fa-route" style="color: #2d5a27; margin-right: 10px;"></i>
                        Tournée #{{ $tournee->id }}
                    </h1>
                    <p style="color: #6c757d; margin: 5px 0 0 0;">
                        {{ $tournee->zone->nom }} — {{ $tournee->date_tournee->format('d/m/Y') }}
                    </p>
                </div>
                <a href="{{ route('admin.tournees.index') }}" style="
                    background: #e8e0d5; color: #2d5a27; padding: 12px 24px; border-radius: 30px;
                    text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 8px;
                ">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>

            @if(session('success'))
                <div style="background: #d4edda; color: #155724; padding: 12px 20px; border-radius: 8px; border-left: 4px solid #28a745; margin-bottom: 20px;">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div style="background: #f8d7da; color: #721c24; padding: 12px 20px; border-radius: 8px; border-left: 4px solid #dc3545; margin-bottom: 20px;">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Infos tournée -->
            <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border: 1px solid #e8e0d5; padding: 25px; margin-bottom: 25px;">
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;">
                    <div>
                        <div style="color: #6c757d; font-size: 0.85rem; margin-bottom: 4px;">Livreur</div>
                        <div style="color: #2d5a27; font-weight: 600;">{{ $tournee->livreur->full_name }}</div>
                        <div style="color: #6c757d; font-size: 0.85rem;">{{ $tournee->livreur->tel }}</div>
                    </div>
                    <div>
                        <div style="color: #6c757d; font-size: 0.85rem; margin-bottom: 4px;">Zone</div>
                        <div style="color: #2d5a27; font-weight: 600;">{{ $tournee->zone->nom }}</div>
                    </div>
                    <div>
                        <div style="color: #6c757d; font-size: 0.85rem; margin-bottom: 4px;">Date</div>
                        <div style="color: #2d5a27; font-weight: 600;">{{ $tournee->date_tournee->format('d/m/Y') }}</div>
                    </div>
                    <div>
                        <div style="color: #6c757d; font-size: 0.85rem; margin-bottom: 4px;">Statut</div>
                        @if($tournee->statut === 'en_cours')
                            <span style="background: #fff3cd; color: #856404; padding: 6px 14px; border-radius: 20px; font-size: 0.85rem; font-weight: 500;">
                                <i class="fas fa-truck-loading"></i> En cours
                            </span>
                        @else
                            <span style="background: #d4edda; color: #155724; padding: 6px 14px; border-radius: 20px; font-size: 0.85rem; font-weight: 500;">
                                <i class="fas fa-check-circle"></i> Terminée
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Liste des commandes -->
            <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); overflow: hidden; border: 1px solid #e8e0d5; margin-bottom: 25px;">
                <div style="padding: 20px 25px; border-bottom: 1px solid #e8e0d5;">
                    <h2 style="font-size: 16px; color: #2d5a27; font-weight: 700; margin: 0;">
                        <i class="fas fa-box" style="color: #b8860b; margin-right: 8px;"></i>
                        Commandes ({{ $tournee->orders->count() }})
                    </h2>
                </div>

                <table style="width: 100%; border-collapse: collapse; table-layout: fixed; font-size: 0.95rem;">
                    <thead style="background: #f8f5f0;">
                        <tr>
                            <th style="padding: 12px 15px; text-align: left; font-weight: 600; color: #2d5a27; width: 15%;">N°</th>
                            <th style="padding: 12px 15px; text-align: left; font-weight: 600; color: #2d5a27; width: 25%;">Client</th>
                            <th style="padding: 12px 15px; text-align: left; font-weight: 600; color: #2d5a27; width: 25%;">Adresse</th>
                            <th style="padding: 12px 15px; text-align: right; font-weight: 600; color: #2d5a27; width: 12%;">Montant</th>
                            <th style="padding: 12px 15px; text-align: center; font-weight: 600; color: #2d5a27; width: 11%;">Statut</th>
                            <th style="padding: 12px 15px; text-align: center; font-weight: 600; color: #2d5a27; width: 12%;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tournee->orders as $order)
                            <tr style="border-bottom: 1px solid #f0ebe5;">
                                <td style="padding: 12px 15px; color: #2d5a27; font-weight: 500;">{{ $order->num_order }}</td>
                                <td style="padding: 12px 15px;">
                                    <div style="color: #2d5a27;">{{ $order->user->prenom ?? $order->user->name ?? 'N/A' }}</div>
                                    <div style="color: #6c757d; font-size: 0.8rem;">{{ $order->user->email ?? '' }}</div>
                                </td>
                                <td style="padding: 12px 15px; color: #2d5a27; font-size: 0.85rem;">
                                    {{ Str::limit($order->adresse_precise, 40) }}
                                </td>
                                <td style="padding: 12px 15px; text-align: right; font-weight: 700; color: #2d5a27;">
                                    {{ number_format($order->montant_ttc ?? $order->mt_total, 0, ',', ' ') }} FCFA
                                </td>
                                <td style="padding: 12px 15px; text-align: center;">
                                    @if($order->statut === 'livree')
                                        <span style="background: #d4edda; color: #155724; padding: 5px 12px; border-radius: 20px; font-size: 0.8rem;">
                                            <i class="fas fa-check"></i> Livrée
                                        </span>
                                    @else
                                        <span style="background: #fff3cd; color: #856404; padding: 5px 12px; border-radius: 20px; font-size: 0.8rem;">
                                            <i class="fas fa-truck"></i> En livraison
                                        </span>
                                    @endif
                                </td>
                                <td style="padding: 12px 15px; text-align: center;">
                                    @if($order->statut !== 'livree' && $tournee->statut === 'en_cours')
                                        <form action="{{ route('admin.tournees.deliver-order', [$tournee, $order]) }}" method="POST"
                                              onsubmit="return confirm('Confirmer la livraison de {{ $order->num_order }} ?');">
                                            @csrf
                                            <button type="submit" style="
                                                background: #2d5a27; color: white; padding: 7px 14px; border-radius: 20px;
                                                border: none; font-size: 0.8rem; cursor: pointer;
                                            ">
                                                <i class="fas fa-check"></i> Marquer livrée
                                            </button>
                                        </form>
                                    @else
                                        <span style="color: #adb5bd; font-size: 0.8rem;">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Clôture -->
            @if($tournee->statut === 'en_cours')
                <div style="
                    background: {{ $tournee->toutesLivrees() ? '#e8f5e9' : '#fff3cd' }};
                    border: 1px solid {{ $tournee->toutesLivrees() ? '#c8e6c9' : '#ffe69c' }};
                    border-radius: 12px; padding: 20px 25px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;
                ">
                    <div style="color: {{ $tournee->toutesLivrees() ? '#2d5a27' : '#856404' }};">
                        @if($tournee->toutesLivrees())
                            <i class="fas fa-check-circle"></i> Toutes les commandes ont été livrées. Vous pouvez clôturer la tournée.
                        @else
                            <i class="fas fa-info-circle"></i> Toutes les commandes doivent être livrées avant de clôturer la tournée.
                        @endif
                    </div>
                    <form action="{{ route('admin.tournees.close', $tournee) }}" method="POST"
                          onsubmit="return confirm('Clôturer cette tournée ?');">
                        @csrf
                        <button type="submit" {{ $tournee->toutesLivrees() ? '' : 'disabled' }} style="
                            background: {{ $tournee->toutesLivrees() ? '#2d5a27' : '#d4c9bb' }};
                            color: white; padding: 12px 30px; border-radius: 30px; border: none;
                            font-weight: 500; cursor: {{ $tournee->toutesLivrees() ? 'pointer' : 'not-allowed' }};
                        ">
                            <i class="fas fa-flag-checkered"></i> Clôturer la tournée
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection