@extends('layouts.admin')

@section('title', 'Détail achat ' . $achat->numero . ' - La Réserve Naturelle')

@section('content')
<div class="container" style="padding: 40px 0;">
    <div class="row">
        <div class="col-12">

            {{-- Entête --}}
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:30px;flex-wrap:wrap;gap:15px;">
                <div>
                    <h1 style="font-family:'Playfair Display',serif;color:#2d5a27;font-size:2rem;margin:0;">
                        <i class="fas fa-file-alt" style="margin-right:10px;"></i>
                        {{ $achat->numero }}
                    </h1>
                    <p style="color:#6c757d;margin:5px 0 0 0;">Détail de la commande d'achat</p>
                </div>
                <div style="display:flex;gap:10px;flex-wrap:wrap;">

                    @if($achat->statut === 'brouillon')
                        <form action="{{ route('admin.achats.confirmer', $achat) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" style="background:#004085;color:white;padding:12px 24px;border-radius:30px;border:none;font-weight:500;cursor:pointer;display:inline-flex;align-items:center;gap:8px;"
                                onmouseover="this.style.background='#002752'" onmouseout="this.style.background='#004085'">
                                <i class="fas fa-check"></i> Confirmer
                            </button>
                        </form>
                    @endif

                    @if(in_array($achat->statut, ['confirme', 'recu_partiel']))
                        <a href="{{ route('admin.achats.reception', $achat) }}" style="background:#155724;color:white;padding:12px 24px;border-radius:30px;text-decoration:none;font-weight:500;display:inline-flex;align-items:center;gap:8px;"
                            onmouseover="this.style.background='#0d3a18'" onmouseout="this.style.background='#155724'">
                            <i class="fas fa-truck-loading"></i> Réceptionner
                        </a>
                    @endif

                    @if(in_array($achat->statut, ['brouillon', 'confirme']))
                        <form action="{{ route('admin.achats.annuler', $achat) }}" method="POST" style="display:inline;" onsubmit="return confirm('Annuler cet achat ?');">
                            @csrf
                            <button type="submit" style="background:#721c24;color:white;padding:12px 24px;border-radius:30px;border:none;font-weight:500;cursor:pointer;display:inline-flex;align-items:center;gap:8px;"
                                onmouseover="this.style.background='#4e1319'" onmouseout="this.style.background='#721c24'">
                                <i class="fas fa-times"></i> Annuler
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('admin.achats.index') }}" style="background:#e8e0d5;color:#2d5a27;padding:12px 24px;border-radius:30px;text-decoration:none;font-weight:500;display:inline-flex;align-items:center;gap:8px;"
                        onmouseover="this.style.background='#d4c9bb'" onmouseout="this.style.background='#e8e0d5'">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                </div>
            </div>

            {{-- Messages flash --}}
            @if(session('success'))
                <div style="background:#d4edda;color:#155724;padding:12px 20px;border-radius:8px;border-left:4px solid #28a745;margin-bottom:20px;display:flex;align-items:center;gap:10px;">
                    <i class="fas fa-check-circle" style="font-size:20px;"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div style="background:#f8d7da;color:#721c24;padding:12px 20px;border-radius:8px;border-left:4px solid #dc3545;margin-bottom:20px;display:flex;align-items:center;gap:10px;">
                    <i class="fas fa-exclamation-circle" style="font-size:20px;"></i> {{ session('error') }}
                </div>
            @endif

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:25px;margin-bottom:25px;">

                {{-- Infos générales --}}
                <div style="background:white;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.08);border:1px solid #e8e0d5;padding:25px;">
                    <h2 style="font-family:'Playfair Display',serif;color:#2d5a27;font-size:1.2rem;margin:0 0 20px 0;padding-bottom:15px;border-bottom:1px solid #e8e0d5;">
                        <i class="fas fa-info-circle" style="margin-right:8px;"></i> Informations générales
                    </h2>

                    @php
                        $badges = [
                            'brouillon'    => ['bg' => '#f8f5f0', 'color' => '#6c757d',  'label' => 'Brouillon',          'icon' => 'fa-pencil-alt'],
                            'confirme'     => ['bg' => '#cce5ff', 'color' => '#004085',  'label' => 'Confirmé',           'icon' => 'fa-check'],
                            'recu_partiel' => ['bg' => '#fff3cd', 'color' => '#856404',  'label' => 'Reçu partiellement', 'icon' => 'fa-clock'],
                            'recu_total'   => ['bg' => '#d4edda', 'color' => '#155724',  'label' => 'Reçu totalement',    'icon' => 'fa-check-double'],
                            'annule'       => ['bg' => '#f8d7da', 'color' => '#721c24',  'label' => 'Annulé',             'icon' => 'fa-times'],
                        ];
                        $badge = $badges[$achat->statut] ?? $badges['brouillon'];
                    @endphp

                    <div style="display:flex;flex-direction:column;gap:15px;">
                        <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:12px;border-bottom:1px solid #f0ebe5;">
                            <span style="color:#6c757d;font-size:0.9rem;">Numéro</span>
                            <strong style="color:#2d5a27;">{{ $achat->numero }}</strong>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:12px;border-bottom:1px solid #f0ebe5;">
                            <span style="color:#6c757d;font-size:0.9rem;">Statut</span>
                            <span style="background:{{ $badge['bg'] }};color:{{ $badge['color'] }};padding:4px 14px;border-radius:20px;font-size:0.8rem;font-weight:500;display:inline-flex;align-items:center;gap:6px;">
                                <i class="fas {{ $badge['icon'] }}"></i> {{ $badge['label'] }}
                            </span>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:12px;border-bottom:1px solid #f0ebe5;">
                            <span style="color:#6c757d;font-size:0.9rem;">Date d'achat</span>
                            <strong>{{ $achat->date_achat->format('d/m/Y') }}</strong>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:12px;border-bottom:1px solid #f0ebe5;">
                            <span style="color:#6c757d;font-size:0.9rem;">Réception prévue</span>
                            <strong>{{ $achat->date_reception_prevue ? $achat->date_reception_prevue->format('d/m/Y') : '—' }}</strong>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:12px;border-bottom:1px solid #f0ebe5;">
                            <span style="color:#6c757d;font-size:0.9rem;">Date de réception</span>
                            <strong>{{ $achat->date_reception ? $achat->date_reception->format('d/m/Y') : '—' }}</strong>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:12px;border-bottom:1px solid #f0ebe5;">
                            <span style="color:#6c757d;font-size:0.9rem;">Créé par</span>
                            <strong>{{ $achat->user->prenom ?? '—' }} {{ $achat->user->nom ?? '' }}</strong>
                        </div>
                        @if($achat->notes)
                            <div style="padding-bottom:12px;">
                                <span style="color:#6c757d;font-size:0.9rem;display:block;margin-bottom:5px;">Notes</span>
                                <p style="margin:0;color:#2d5a27;background:#f8f5f0;padding:10px 15px;border-radius:8px;">{{ $achat->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Infos fournisseur + paiement --}}
                <div style="display:flex;flex-direction:column;gap:25px;">

                    <div style="background:white;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.08);border:1px solid #e8e0d5;padding:25px;">
                        <h2 style="font-family:'Playfair Display',serif;color:#2d5a27;font-size:1.2rem;margin:0 0 20px 0;padding-bottom:15px;border-bottom:1px solid #e8e0d5;">
                            <i class="fas fa-truck" style="margin-right:8px;"></i> Fournisseur
                        </h2>
                        <div style="display:flex;align-items:center;gap:15px;">
                            <div style="width:50px;height:50px;border-radius:50%;background:#2d5a27;color:white;display:flex;align-items:center;justify-content:center;font-weight:bold;font-size:18px;flex-shrink:0;">
                                {{ strtoupper(substr($achat->fournisseur->prenom ?? '', 0, 1)) }}{{ strtoupper(substr($achat->fournisseur->nom ?? '', 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight:700;color:#2d5a27;font-size:1.1rem;">{{ $achat->fournisseur->prenom }} {{ $achat->fournisseur->nom }}</div>
                                <div style="color:#6c757d;font-size:0.9rem;margin-top:4px;">
                                    <i class="fas fa-phone" style="margin-right:5px;"></i> {{ $achat->fournisseur->tel ?? '—' }}
                                </div>
                                <div style="color:#6c757d;font-size:0.9rem;margin-top:2px;">
                                    <i class="fas fa-map-pin" style="margin-right:5px;"></i> {{ $achat->fournisseur->ville ?? '—' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div style="background:white;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.08);border:1px solid #e8e0d5;padding:25px;">
                        <h2 style="font-family:'Playfair Display',serif;color:#2d5a27;font-size:1.2rem;margin:0 0 20px 0;padding-bottom:15px;border-bottom:1px solid #e8e0d5;">
                            <i class="fas fa-wallet" style="margin-right:8px;"></i> Paiement
                        </h2>
                        <div style="display:flex;flex-direction:column;gap:12px;">
                            <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:12px;border-bottom:1px solid #f0ebe5;">
                                <span style="color:#6c757d;font-size:0.9rem;">Montant total</span>
                                <strong style="color:#2d5a27;font-size:1.1rem;">{{ number_format($achat->mt_total, 0, ',', ' ') }} FCFA</strong>
                            </div>
                            <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:12px;border-bottom:1px solid #f0ebe5;">
                                <span style="color:#6c757d;font-size:0.9rem;">Montant payé</span>
                                <strong style="color:#155724;font-size:1.1rem;">{{ number_format($achat->mt_paye, 0, ',', ' ') }} FCFA</strong>
                            </div>
                            <div style="display:flex;justify-content:space-between;align-items:center;">
                                <span style="color:#6c757d;font-size:0.9rem;">Date paiement</span>
                                <strong>{{ $achat->date_paiement ? $achat->date_paiement->format('d/m/Y') : '—' }}</strong>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Lignes produits --}}
            <div style="background:white;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.08);border:1px solid #e8e0d5;padding:25px;margin-bottom:25px;">
                <h2 style="font-family:'Playfair Display',serif;color:#2d5a27;font-size:1.2rem;margin:0 0 20px 0;padding-bottom:15px;border-bottom:1px solid #e8e0d5;">
                    <i class="fas fa-list" style="margin-right:8px;"></i> Lignes de la commande
                </h2>
                <div style="overflow-x:auto;">
                    <table style="width:100%;border-collapse:collapse;font-size:0.95rem;">
                        <thead style="background:#f8f5f0;border-bottom:2px solid #e8e0d5;">
                            <tr>
                                <th style="padding:12px 20px;text-align:left;font-weight:600;color:#2d5a27;">Produit</th>
                                <th style="padding:12px 20px;text-align:center;font-weight:600;color:#2d5a27;">Commandé</th>
                                <th style="padding:12px 20px;text-align:center;font-weight:600;color:#2d5a27;">Reçu</th>
                                <th style="padding:12px 20px;text-align:center;font-weight:600;color:#2d5a27;">Reliquat</th>
                                <th style="padding:12px 20px;text-align:right;font-weight:600;color:#2d5a27;">Prix unitaire</th>
                                <th style="padding:12px 20px;text-align:right;font-weight:600;color:#2d5a27;">Sous-total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($achat->produits as $ligne)
                                <tr style="border-bottom:1px solid #f0ebe5;">
                                    <td style="padding:12px 20px;font-weight:500;color:#2d5a27;">
                                        {{ $ligne->product->designation }}
                                        <div style="font-size:0.8rem;color:#6c757d;">{{ $ligne->product->reference_prod }}</div>
                                    </td>
                                    <td style="padding:12px 20px;text-align:center;font-weight:600;">
                                        {{ $ligne->qte_commandee }}
                                    </td>
                                    <td style="padding:12px 20px;text-align:center;">
                                        <span style="color:{{ $ligne->qte_recue >= $ligne->qte_commandee ? '#155724' : '#856404' }};font-weight:600;">
                                            {{ $ligne->qte_recue }}
                                        </span>
                                    </td>
                                    <td style="padding:12px 20px;text-align:center;">
                                        @if($ligne->reliquat > 0)
                                            <span style="background:#fff3cd;color:#856404;padding:3px 10px;border-radius:20px;font-size:0.85rem;font-weight:500;">
                                                {{ $ligne->reliquat }}
                                            </span>
                                        @else
                                            <span style="background:#d4edda;color:#155724;padding:3px 10px;border-radius:20px;font-size:0.85rem;font-weight:500;">
                                                <i class="fas fa-check"></i> Complet
                                            </span>
                                        @endif
                                    </td>
                                    <td style="padding:12px 20px;text-align:right;color:#6c757d;">
                                        {{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} FCFA
                                    </td>
                                    <td style="padding:12px 20px;text-align:right;font-weight:600;color:#2d5a27;">
                                        {{ number_format($ligne->qte_commandee * $ligne->prix_unitaire, 0, ',', ' ') }} FCFA
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="background:#f8f5f0;border-top:2px solid #e8e0d5;">
                                <td colspan="5" style="padding:15px 20px;text-align:right;font-weight:700;color:#2d5a27;">Total</td>
                                <td style="padding:15px 20px;text-align:right;font-weight:700;color:#2d5a27;font-size:1.1rem;">
                                    {{ number_format($achat->mt_total, 0, ',', ' ') }} FCFA
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Historique des mouvements --}}
            @if($achat->stockMouvements->count() > 0)
                <div style="background:white;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.08);border:1px solid #e8e0d5;padding:25px;">
                    <h2 style="font-family:'Playfair Display',serif;color:#2d5a27;font-size:1.2rem;margin:0 0 20px 0;padding-bottom:15px;border-bottom:1px solid #e8e0d5;">
                        <i class="fas fa-history" style="margin-right:8px;"></i> Mouvements de stock générés
                    </h2>
                    <div style="overflow-x:auto;">
                        <table style="width:100%;border-collapse:collapse;font-size:0.9rem;">
                            <thead style="background:#f8f5f0;border-bottom:2px solid #e8e0d5;">
                                <tr>
                                    <th style="padding:12px 20px;text-align:left;font-weight:600;color:#2d5a27;">Produit</th>
                                    <th style="padding:12px 20px;text-align:center;font-weight:600;color:#2d5a27;">Quantité</th>
                                    <th style="padding:12px 20px;text-align:center;font-weight:600;color:#2d5a27;">Stock avant</th>
                                    <th style="padding:12px 20px;text-align:center;font-weight:600;color:#2d5a27;">Stock après</th>
                                    <th style="padding:12px 20px;text-align:center;font-weight:600;color:#2d5a27;">CMP après</th>
                                    <th style="padding:12px 20px;text-align:center;font-weight:600;color:#2d5a27;">Par</th>
                                    <th style="padding:12px 20px;text-align:center;font-weight:600;color:#2d5a27;">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($achat->stockMouvements as $mvt)
                                    <tr style="border-bottom:1px solid #f0ebe5;">
                                        <td style="padding:12px 20px;font-weight:500;color:#2d5a27;">
                                            {{ $mvt->product->designation }}
                                        </td>
                                        <td style="padding:12px 20px;text-align:center;">
                                            <span style="background:#d4edda;color:#155724;padding:3px 12px;border-radius:20px;font-weight:600;">
                                                +{{ $mvt->quantite }}
                                            </span>
                                        </td>
                                        <td style="padding:12px 20px;text-align:center;color:#6c757d;">{{ $mvt->stock_avant }}</td>
                                        <td style="padding:12px 20px;text-align:center;font-weight:600;color:#2d5a27;">{{ $mvt->stock_apres }}</td>
                                        <td style="padding:12px 20px;text-align:center;color:#6c757d;">{{ number_format($mvt->cmp_apres, 0, ',', ' ') }} FCFA</td>
                                        <td style="padding:12px 20px;text-align:center;color:#6c757d;">{{ $mvt->user->prenom ?? '—' }}</td>
                                        <td style="padding:12px 20px;text-align:center;color:#6c757d;">{{ $mvt->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection