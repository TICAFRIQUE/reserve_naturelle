{{-- resources/views/admin/rapports/stock.blade.php --}}
@extends('layouts.admin')

@section('title', 'Rapports - Gestion de stock - La Réserve Naturelle')

@section('content')
<div class="container" style="padding: 40px 0;">
    <div class="row">
        <div class="col-12">
            <!-- Entête -->
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 25px; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h1 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 2rem; margin: 0;">
                        <i class="fas fa-chart-bar" style="color: #b8860b; margin-right: 10px;"></i>
                        Rapports - Gestion de stock
                    </h1>
                    <p style="color: #6c757d; margin: 5px 0 0 0;">Consultez les rapports et statistiques de votre stock</p>
                </div>
                <div style="display: flex; gap: 10px;">
                    <a href="{{ route('admin.rapports.stock_pdf', ['onglet' => $onglet, 'date_from' => $dateFrom, 'date_to' => $dateTo]) }}" style="
                        background: #2d5a27; color: white; padding: 12px 22px; border-radius: 30px;
                        text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 8px;
                    " onmouseover="this.style.background='#1e3d1a'" onmouseout="this.style.background='#2d5a27'">
                        <i class="fas fa-file-pdf"></i> Exporter en PDF
                    </a>
                    <button type="button" onclick="window.print()" style="
                        background: #f0ebe5; color: #2d5a27; padding: 12px 22px; border-radius: 30px; border: none;
                        font-weight: 500; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;
                    " onmouseover="this.style.background='#e0d6c8'" onmouseout="this.style.background='#f0ebe5'">
                        <i class="fas fa-print"></i> Imprimer
                    </button>
                </div>
            </div>

            <!-- Filtre dates -->
            <div style="background: #f8f5f0; padding: 18px 20px; border-radius: 12px; margin-bottom: 20px; border: 1px solid #e8e0d5;">
                <form action="{{ route('admin.rapports.stock') }}" method="GET" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end;">
                    <input type="hidden" name="onglet" value="{{ $onglet }}">
                    <div style="flex: 1; min-width: 150px;">
                        <label style="display: block; font-weight: 600; color: #2d5a27; font-size: 0.9rem; margin-bottom: 5px;">Du</label>
                        <input type="date" name="date_from" value="{{ $dateFrom }}" style="width: 100%; padding: 10px 15px; border: 1px solid #e8e0d5; border-radius: 8px; font-size: 0.95rem; outline: none; background: white;">
                    </div>
                    <div style="flex: 1; min-width: 150px;">
                        <label style="display: block; font-weight: 600; color: #2d5a27; font-size: 0.9rem; margin-bottom: 5px;">Au</label>
                        <input type="date" name="date_to" value="{{ $dateTo }}" style="width: 100%; padding: 10px 15px; border: 1px solid #e8e0d5; border-radius: 8px; font-size: 0.95rem; outline: none; background: white;">
                    </div>
                    <button type="submit" style="background: #2d5a27; color: white; padding: 10px 25px; border-radius: 30px; border: none; font-weight: 500; cursor: pointer;">
                        <i class="fas fa-filter"></i> Filtrer
                    </button>
                </form>
            </div>

            <!-- Cartes -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 25px;">
                <a href="{{ route('admin.rapports.stock', ['onglet' => 'produits', 'date_from' => $dateFrom, 'date_to' => $dateTo]) }}" style="text-decoration: none; background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border: 1px solid #e8e0d5; padding: 22px; display: block; transition: transform 0.15s;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='none'">
                    <div style="width: 46px; height: 46px; border-radius: 10px; background: #e8f5e9; display: flex; align-items: center; justify-content: center; margin-bottom: 12px;">
                        <i class="fas fa-box" style="color: #2d5a27; font-size: 18px;"></i>
                    </div>
                    <div style="color: #6c757d; font-size: 0.85rem;">Valeur du stock actuel</div>
                    <div style="color: #2d5a27; font-size: 1.3rem; font-weight: 700;">{{ number_format($valeur_stock, 0, ',', ' ') }} FCFA</div>
                </a>
                <a href="{{ route('admin.rapports.stock', ['onglet' => 'achats', 'date_from' => $dateFrom, 'date_to' => $dateTo]) }}" style="text-decoration: none; background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border: 1px solid #e8e0d5; padding: 22px; display: block; transition: transform 0.15s;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='none'">
                    <div style="width: 46px; height: 46px; border-radius: 10px; background: #e3f2fd; display: flex; align-items: center; justify-content: center; margin-bottom: 12px;">
                        <i class="fas fa-cart-arrow-down" style="color: #1565c0; font-size: 18px;"></i>
                    </div>
                    <div style="color: #6c757d; font-size: 0.85rem;">Total des entrées (achats)</div>
                    <div style="color: #2d5a27; font-size: 1.3rem; font-weight: 700;">{{ number_format($entrees, 0, ',', ' ') }} FCFA</div>
                    @if(!is_null($entrees_var))
                        <div style="font-size: 0.8rem; color: {{ $entrees_var >= 0 ? '#2e7d32' : '#c62828' }};">
                            <i class="fas fa-arrow-{{ $entrees_var >= 0 ? 'up' : 'down' }}"></i> {{ abs($entrees_var) }}% vs période précédente
                        </div>
                    @endif
                </a>
                <a href="{{ route('admin.rapports.stock', ['onglet' => 'ventes', 'date_from' => $dateFrom, 'date_to' => $dateTo]) }}" style="text-decoration: none; background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border: 1px solid #e8e0d5; padding: 22px; display: block; transition: transform 0.15s;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='none'">
                    <div style="width: 46px; height: 46px; border-radius: 10px; background: #fff3e0; display: flex; align-items: center; justify-content: center; margin-bottom: 12px;">
                        <i class="fas fa-exchange-alt" style="color: #e65100; font-size: 18px;"></i>
                    </div>
                    <div style="color: #6c757d; font-size: 0.85rem;">Total des sorties (ventes)</div>
                    <div style="color: #2d5a27; font-size: 1.3rem; font-weight: 700;">{{ number_format($sorties, 0, ',', ' ') }} FCFA</div>
                    @if(!is_null($sorties_var))
                        <div style="font-size: 0.8rem; color: {{ $sorties_var >= 0 ? '#2e7d32' : '#c62828' }};">
                            <i class="fas fa-arrow-{{ $sorties_var >= 0 ? 'up' : 'down' }}"></i> {{ abs($sorties_var) }}% vs période précédente
                        </div>
                    @endif
                </a>
                <a href="{{ route('admin.depenses.index', ['date_from' => $dateFrom, 'date_to' => $dateTo]) }}" style="text-decoration: none; background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border: 1px solid #e8e0d5; padding: 22px; display: block; transition: transform 0.15s;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='none'">
                    <div style="width: 46px; height: 46px; border-radius: 10px; background: #f8d7da; display: flex; align-items: center; justify-content: center; margin-bottom: 12px;">
                        <i class="fas fa-wallet" style="color: #721c24; font-size: 18px;"></i>
                    </div>
                    <div style="color: #6c757d; font-size: 0.85rem;">Dépenses</div>
                    <div style="color: #721c24; font-size: 1.3rem; font-weight: 700;">{{ number_format($depenses, 0, ',', ' ') }} FCFA</div>
                    @if(!is_null($depenses_var))
                        <div style="font-size: 0.8rem; color: {{ $depenses_var <= 0 ? '#2e7d32' : '#c62828' }};">
                            <i class="fas fa-arrow-{{ $depenses_var >= 0 ? 'up' : 'down' }}"></i> {{ abs($depenses_var) }}% vs période précédente
                        </div>
                    @endif
                    <div style="font-size: 0.75rem; color: #b8860b; margin-top: 4px;"><i class="fas fa-arrow-right"></i> Voir le détail</div>
                </a>
                <a href="{{ route('admin.rapports.stock', ['onglet' => 'produits', 'date_from' => $dateFrom, 'date_to' => $dateTo]) }}" style="text-decoration: none; background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border: 1px solid #e8e0d5; padding: 22px; display: block; transition: transform 0.15s;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='none'">
                    <div style="width: 46px; height: 46px; border-radius: 10px; background: #fdecea; display: flex; align-items: center; justify-content: center; margin-bottom: 12px;">
                        <i class="fas fa-exclamation-triangle" style="color: #c62828; font-size: 18px;"></i>
                    </div>
                    <div style="color: #6c757d; font-size: 0.85rem;">Produits en alerte (stock faible)</div>
                    <div style="color: #2d5a27; font-size: 1.3rem; font-weight: 700;">{{ $produits_alerte }}</div>
                    <div style="font-size: 0.8rem; color: #c62828;">{{ $produits_rupture }} produit(s) en rupture</div>
                </a>
            </div>

            <!-- Onglets -->
            <div style="display: flex; gap: 5px; border-bottom: 2px solid #e8e0d5; margin-bottom: 25px; flex-wrap: wrap;">
                @foreach([
                    'vue-ensemble' => 'Vue d\'ensemble',
                    'mouvements'   => 'Mouvements de stock',
                    'produits'     => 'Produits',
                    'achats'       => 'Achats',
                    'fournisseurs' => 'Fournisseurs',
                    'users'        => 'Utilisateurs',
                    'ventes'       => 'Total ventes',
                ] as $key => $label)
                    <a href="{{ route('admin.rapports.stock', ['onglet' => $key, 'date_from' => $dateFrom, 'date_to' => $dateTo]) }}" style="
                        padding: 12px 18px; text-decoration: none; font-weight: 600; font-size: 0.92rem;
                        color: {{ $onglet == $key ? '#2d5a27' : '#6c757d' }};
                        border-bottom: 3px solid {{ $onglet == $key ? '#2d5a27' : 'transparent' }};
                        margin-bottom: -2px; transition: color 0.2s;
                    ">{{ $label }}</a>
                @endforeach
            </div>

            <!-- Contenu de l'onglet -->
            <div style="background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow: hidden; border: 1px solid #e8e0d5;">

                @if(in_array($onglet, ['vue-ensemble', 'mouvements']))
                    <h3 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 1.15rem; margin: 0; padding: 20px 25px;">
                        <i class="fas fa-list" style="color: #b8860b;"></i> Détail des mouvements de stock
                    </h3>
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
                            <thead style="background: #f8f5f0; border-bottom: 2px solid #e8e0d5;">
                                <tr>
                                    <th style="padding: 12px 20px; text-align: left; color: #2d5a27;">Date</th>
                                    <th style="padding: 12px 20px; text-align: left; color: #2d5a27;">Produit</th>
                                    <th style="padding: 12px 20px; text-align: left; color: #2d5a27;">Type</th>
                                    <th style="padding: 12px 20px; text-align: right; color: #2d5a27;">Quantité</th>
                                    <th style="padding: 12px 20px; text-align: right; color: #2d5a27;">Prix unitaire</th>
                                    <th style="padding: 12px 20px; text-align: right; color: #2d5a27;">Valeur</th>
                                    <th style="padding: 12px 20px; text-align: left; color: #2d5a27;">Référence</th>
                                    <th style="padding: 12px 20px; text-align: left; color: #2d5a27;">Utilisateur</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($mouvements as $m)
                                    @php
                                        $reference = match(true) {
                                            $m->source instanceof \App\Models\Achat => $m->source->numero,
                                            $m->source instanceof \App\Models\Order => $m->source->num_order,
                                            $m->source instanceof \App\Models\Inventaire => $m->source->reference,
                                            default => '—',
                                        };
                                        $estEntree = $m->sens === 'entree';
                                    @endphp
                                    <tr style="border-bottom: 1px solid #f0ebe5;">
                                        <td style="padding: 12px 20px; color: #6c757d;">{{ $m->created_at->format('d/m/Y H:i') }}</td>
                                        <td style="padding: 12px 20px; font-weight: 500; color: #2d5a27;">{{ $m->product->designation ?? '—' }}</td>
                                        <td style="padding: 12px 20px;">
                                            <span style="
                                                background: {{ $estEntree ? '#e8f5e9' : '#e3f2fd' }};
                                                color: {{ $estEntree ? '#2e7d32' : '#1565c0' }};
                                                padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 600;
                                            ">{{ $estEntree ? 'Entrée' : 'Sortie' }} ({{ ucfirst($m->type) }})</span>
                                        </td>
                                        <td style="padding: 12px 20px; text-align: right;">{{ $m->quantite }}</td>
                                        <td style="padding: 12px 20px; text-align: right;">{{ number_format($m->cmp_apres, 0, ',', ' ') }}</td>
                                        <td style="padding: 12px 20px; text-align: right; font-weight: 600; color: #2d5a27;">{{ number_format($m->quantite * $m->cmp_apres, 0, ',', ' ') }}</td>
                                        <td style="padding: 12px 20px; color: #6c757d;">{{ $reference }}</td>
                                        <td style="padding: 12px 20px; color: #6c757d;">{{ $m->user->prenom ?? '—' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="8" style="padding: 40px 20px; text-align: center; color: #6c757d;">Aucun mouvement sur cette période</td></tr>
                                @endforelse
                            </tbody>
                            @if($mouvements->isNotEmpty())
                                <tfoot>
                                    <tr style="background: #f8f5f0; border-top: 2px solid #e8e0d5;">
                                        <td colspan="5" style="padding: 12px 20px; text-align: right; font-weight: 700; color: #2d5a27;">Total</td>
                                        <td style="padding: 12px 20px; text-align: right; font-weight: 700; color: #2d5a27;">{{ number_format($mouvementsTotal, 0, ',', ' ') }} FCFA</td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                    @if($mouvements instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div style="padding: 15px 25px;">{{ $mouvements->links() }}</div>
                    @endif
                @endif

                @if($onglet == 'produits')
                    <h3 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 1.15rem; margin: 0; padding: 20px 25px;">
                        <i class="fas fa-boxes" style="color: #b8860b;"></i> État des produits
                    </h3>
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
                            <thead style="background: #f8f5f0; border-bottom: 2px solid #e8e0d5;">
                                <tr>
                                    <th style="padding: 12px 20px; text-align: left; color: #2d5a27;">Produit</th>
                                    <th style="padding: 12px 20px; text-align: left; color: #2d5a27;">Catégorie</th>
                                    <th style="padding: 12px 20px; text-align: right; color: #2d5a27;">Qté dispo</th>
                                    <th style="padding: 12px 20px; text-align: right; color: #2d5a27;">Seuil min</th>
                                    <th style="padding: 12px 20px; text-align: right; color: #2d5a27;">CMP</th>
                                    <th style="padding: 12px 20px; text-align: right; color: #2d5a27;">Valeur stock</th>
                                    <th style="padding: 12px 20px; text-align: center; color: #2d5a27;">Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($produits as $p)
                                    <tr style="border-bottom: 1px solid #f0ebe5;">
                                        <td style="padding: 12px 20px; font-weight: 500; color: #2d5a27;">{{ $p->designation }}</td>
                                        <td style="padding: 12px 20px; color: #6c757d;">{{ $p->category->nom ?? '—' }}</td>
                                        <td style="padding: 12px 20px; text-align: right;">{{ $p->qte_dispo }}</td>
                                        <td style="padding: 12px 20px; text-align: right; color: #6c757d;">{{ $p->stock_minimum }}</td>
                                        <td style="padding: 12px 20px; text-align: right;">{{ number_format($p->cmp, 0, ',', ' ') }}</td>
                                        <td style="padding: 12px 20px; text-align: right; font-weight: 600; color: #2d5a27;">{{ number_format($p->qte_dispo * $p->cmp, 0, ',', ' ') }}</td>
                                        <td style="padding: 12px 20px; text-align: center;">
                                            @if($p->qte_dispo <= 0)
                                                <span style="background: #fdecea; color: #c62828; padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 600;">Rupture</span>
                                            @elseif($p->sous_seuil)
                                                <span style="background: #fff3e0; color: #e65100; padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 600;">Faible</span>
                                            @else
                                                <span style="background: #e8f5e9; color: #2e7d32; padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 600;">OK</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" style="padding: 40px 20px; text-align: center; color: #6c757d;">Aucun produit</td></tr>
                                @endforelse
                            </tbody>
                            @if($produits->isNotEmpty())
                                <tfoot>
                                    <tr style="background: #f8f5f0; border-top: 2px solid #e8e0d5;">
                                        <td colspan="5" style="padding: 12px 20px; text-align: right; font-weight: 700; color: #2d5a27;">Total</td>
                                        <td style="padding: 12px 20px; text-align: right; font-weight: 700; color: #2d5a27;">{{ number_format($produitsTotal, 0, ',', ' ') }} FCFA</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                    <div style="padding: 15px 25px;">{{ $produits->links() }}</div>
                @endif

                @if($onglet == 'achats')
                    <h3 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 1.15rem; margin: 0; padding: 20px 25px;">
                        <i class="fas fa-shopping-basket" style="color: #b8860b;"></i> Achats de la période
                    </h3>
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
                            <thead style="background: #f8f5f0; border-bottom: 2px solid #e8e0d5;">
                                <tr>
                                    <th style="padding: 12px 20px; text-align: left; color: #2d5a27;">N°</th>
                                    <th style="padding: 12px 20px; text-align: left; color: #2d5a27;">Fournisseur</th>
                                    <th style="padding: 12px 20px; text-align: left; color: #2d5a27;">Date</th>
                                    <th style="padding: 12px 20px; text-align: left; color: #2d5a27;">Statut</th>
                                    <th style="padding: 12px 20px; text-align: right; color: #2d5a27;">Montant total</th>
                                    <th style="padding: 12px 20px; text-align: right; color: #2d5a27;">Montant payé</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($achats as $a)
                                    <tr style="border-bottom: 1px solid #f0ebe5;">
                                        <td style="padding: 12px 20px; font-weight: 500; color: #2d5a27;">{{ $a->numero }}</td>
                                        <td style="padding: 12px 20px; color: #6c757d;">{{ $a->fournisseur->full_name ?? '—' }}</td>
                                        <td style="padding: 12px 20px; color: #6c757d;">{{ $a->date_achat?->format('d/m/Y') }}</td>
                                        <td style="padding: 12px 20px; text-transform: capitalize;">{{ $a->statut }}</td>
                                        <td style="padding: 12px 20px; text-align: right; font-weight: 600; color: #2d5a27;">{{ number_format($a->mt_total, 0, ',', ' ') }}</td>
                                        <td style="padding: 12px 20px; text-align: right; color: #6c757d;">{{ number_format($a->mt_paye, 0, ',', ' ') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" style="padding: 40px 20px; text-align: center; color: #6c757d;">Aucun achat sur cette période</td></tr>
                                @endforelse
                            </tbody>
                            @if($achats->isNotEmpty())
                                <tfoot>
                                    <tr style="background: #f8f5f0; border-top: 2px solid #e8e0d5;">
                                        <td colspan="4" style="padding: 12px 20px; text-align: right; font-weight: 700; color: #2d5a27;">Total</td>
                                        <td style="padding: 12px 20px; text-align: right; font-weight: 700; color: #2d5a27;">{{ number_format($achatsTotal, 0, ',', ' ') }} FCFA</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                    <div style="padding: 15px 25px;">{{ $achats->links() }}</div>
                @endif

                @if($onglet == 'fournisseurs')
                    <h3 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 1.15rem; margin: 0; padding: 20px 25px;">
                        <i class="fas fa-truck" style="color: #b8860b;"></i> Fournisseurs — activité sur la période
                    </h3>
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
                            <thead style="background: #f8f5f0; border-bottom: 2px solid #e8e0d5;">
                                <tr>
                                    <th style="padding: 12px 20px; text-align: left; color: #2d5a27;">Fournisseur</th>
                                    <th style="padding: 12px 20px; text-align: left; color: #2d5a27;">Ville</th>
                                    <th style="padding: 12px 20px; text-align: right; color: #2d5a27;">Nb achats</th>
                                    <th style="padding: 12px 20px; text-align: right; color: #2d5a27;">Total achats</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($fournisseurs as $f)
                                    <tr style="border-bottom: 1px solid #f0ebe5;">
                                        <td style="padding: 12px 20px; font-weight: 500; color: #2d5a27;">{{ $f->full_name }}</td>
                                        <td style="padding: 12px 20px; color: #6c757d;">{{ $f->ville }}</td>
                                        <td style="padding: 12px 20px; text-align: right;">{{ $f->achats_count }}</td>
                                        <td style="padding: 12px 20px; text-align: right; font-weight: 600; color: #2d5a27;">{{ number_format($f->achats_sum_mt_total ?? 0, 0, ',', ' ') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" style="padding: 40px 20px; text-align: center; color: #6c757d;">Aucun fournisseur</td></tr>
                                @endforelse
                            </tbody>
                            @if($fournisseurs->isNotEmpty())
                                <tfoot>
                                    <tr style="background: #f8f5f0; border-top: 2px solid #e8e0d5;">
                                        <td colspan="3" style="padding: 12px 20px; text-align: right; font-weight: 700; color: #2d5a27;">Total</td>
                                        <td style="padding: 12px 20px; text-align: right; font-weight: 700; color: #2d5a27;">{{ number_format($fournisseursTotal, 0, ',', ' ') }} FCFA</td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                    <div style="padding: 15px 25px;">{{ $fournisseurs->links() }}</div>
                @endif

                @if($onglet == 'users')
                    <h3 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 1.15rem; margin: 0; padding: 20px 25px;">
                        <i class="fas fa-users" style="color: #b8860b;"></i> Utilisateurs — activité sur la période
                    </h3>
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
                            <thead style="background: #f8f5f0; border-bottom: 2px solid #e8e0d5;">
                                <tr>
                                    <th style="padding: 12px 20px; text-align: left; color: #2d5a27;">Utilisateur</th>
                                    <th style="padding: 12px 20px; text-align: left; color: #2d5a27;">Email</th>
                                    <th style="padding: 12px 20px; text-align: right; color: #2d5a27;">Nb commandes</th>
                                    <th style="padding: 12px 20px; text-align: right; color: #2d5a27;">Total dépensé</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $u)
                                    <tr style="border-bottom: 1px solid #f0ebe5;">
                                        <td style="padding: 12px 20px; font-weight: 500; color: #2d5a27;">{{ $u->prenom }} {{ $u->nom }}</td>
                                        <td style="padding: 12px 20px; color: #6c757d;">{{ $u->email }}</td>
                                        <td style="padding: 12px 20px; text-align: right;">{{ $u->orders_count }}</td>
                                        <td style="padding: 12px 20px; text-align: right; font-weight: 600; color: #2d5a27;">{{ number_format($u->orders_sum_montant_ttc ?? 0, 0, ',', ' ') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" style="padding: 40px 20px; text-align: center; color: #6c757d;">Aucun utilisateur</td></tr>
                                @endforelse
                            </tbody>
                            @if($users->isNotEmpty())
                                <tfoot>
                                    <tr style="background: #f8f5f0; border-top: 2px solid #e8e0d5;">
                                        <td colspan="3" style="padding: 12px 20px; text-align: right; font-weight: 700; color: #2d5a27;">Total</td>
                                        <td style="padding: 12px 20px; text-align: right; font-weight: 700; color: #2d5a27;">{{ number_format($usersTotal, 0, ',', ' ') }} FCFA</td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                    <div style="padding: 15px 25px;">{{ $users->links() }}</div>
                @endif

                @if($onglet == 'ventes')
                    <h3 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 1.15rem; margin: 0; padding: 20px 25px;">
                        <i class="fas fa-shopping-cart" style="color: #b8860b;"></i> Total des ventes de la période
                    </h3>
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
                            <thead style="background: #f8f5f0; border-bottom: 2px solid #e8e0d5;">
                                <tr>
                                    <th style="padding: 12px 20px; text-align: left; color: #2d5a27;">N° commande</th>
                                    <th style="padding: 12px 20px; text-align: left; color: #2d5a27;">Date</th>
                                    <th style="padding: 12px 20px; text-align: left; color: #2d5a27;">Client</th>
                                    <th style="padding: 12px 20px; text-align: left; color: #2d5a27;">Zone</th>
                                    <th style="padding: 12px 20px; text-align: left; color: #2d5a27;">Statut</th>
                                    <th style="padding: 12px 20px; text-align: right; color: #2d5a27;">Montant TTC</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ventes as $v)
                                    <tr style="border-bottom: 1px solid #f0ebe5;">
                                        <td style="padding: 12px 20px; font-weight: 500; color: #2d5a27;">{{ $v->num_order }}</td>
                                        <td style="padding: 12px 20px; color: #6c757d;">{{ $v->date_order?->format('d/m/Y') }}</td>
                                        <td style="padding: 12px 20px; color: #6c757d;">{{ $v->user->prenom ?? '—' }}</td>
                                        <td style="padding: 12px 20px; color: #6c757d;">{{ $v->zone->nom ?? '—' }}</td>
                                        <td style="padding: 12px 20px;">
                                            <span style="
                                                background: {{ $v->statut === 'livree' ? '#e8f5e9' : '#fff3e0' }};
                                                color: {{ $v->statut === 'livree' ? '#2e7d32' : '#e65100' }};
                                                padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 600; text-transform: capitalize;
                                            ">{{ $v->statut }}</span>
                                        </td>
                                        <td style="padding: 12px 20px; text-align: right; font-weight: 600; color: #2d5a27;">{{ number_format($v->montant_ttc, 0, ',', ' ') }} FCFA</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" style="padding: 40px 20px; text-align: center; color: #6c757d;">Aucune vente sur cette période</td></tr>
                                @endforelse
                            </tbody>
                            @if($ventes->isNotEmpty())
                                <tfoot>
                                    <tr style="background: #f8f5f0; border-top: 2px solid #e8e0d5;">
                                        <td colspan="5" style="padding: 12px 20px; text-align: right; font-weight: 700; color: #2d5a27;">Total</td>
                                        <td style="padding: 12px 20px; text-align: right; font-weight: 700; color: #2d5a27;">{{ number_format($ventesTotal, 0, ',', ' ') }} FCFA</td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                    <div style="padding: 15px 25px;">{{ $ventes->links() }}</div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .pagination { display: flex; list-style: none; gap: 5px; padding: 0; margin: 0; }
    .pagination li a, .pagination li span {
        display: inline-block; padding: 8px 16px; background: white; border: 1px solid #e8e0d5;
        border-radius: 6px; color: #2d5a27; text-decoration: none; font-size: 0.9rem;
    }
    .pagination li.active span { background: #2d5a27; color: white; border-color: #2d5a27; }
    .pagination li.disabled span { color: #adb5bd; background: #f8f5f0; }

    @media print {
        .admin-sidebar, .admin-sidebar-overlay, header.header, .admin-footer,
        form, .pagination, button, a[href*="export"] { display: none !important; }
        .admin-main-wrapper, .admin-main { margin: 0 !important; padding: 0 !important; }
        body { background: white !important; }
        table { font-size: 10px !important; }
    }
</style>
@endpush