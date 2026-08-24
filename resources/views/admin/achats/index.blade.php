@extends('layouts.admin')

@section('title', 'Achats - La Réserve Naturelle')

@section('content')
<div class="container" style="padding: 40px 0;">
    <div class="row">
        <div class="col-12">

            {{-- Entête --}}
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h1 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 2rem; margin: 0;">
                        <i class="fas fa-shopping-basket" style="margin-right: 10px;"></i>
                        Commandes d'achat
                    </h1>
                    <p style="color: #6c757d; margin: 5px 0 0 0;">Gérez vos approvisionnements fournisseurs</p>
                </div>
                <a href="{{ route('admin.achats.create') }}" style="
                    background: #2d5a27;
                    color: white;
                    padding: 12px 24px;
                    border-radius: 30px;
                    text-decoration: none;
                    font-weight: 500;
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                    transition: all 0.3s ease;
                " onmouseover="this.style.background='#1e3d1a'" onmouseout="this.style.background='#2d5a27'">
                    <i class="fas fa-plus-circle"></i> Nouvelle commande
                </a>
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

            {{-- Filtres --}}
            <div style="background:#f8f5f0;padding:20px;border-radius:12px;margin-bottom:25px;border:1px solid #e8e0d5;">
                <form action="{{ route('admin.achats.index') }}" method="GET" style="display:flex;flex-wrap:wrap;gap:15px;align-items:flex-end;">

                    <div style="flex:1;min-width:160px;">
                        <label style="display:block;font-weight:600;color:#2d5a27;font-size:0.9rem;margin-bottom:5px;">
                            <i class="fas fa-filter" style="margin-right:5px;"></i> Statut
                        </label>
                        <select name="statut" style="width:100%;padding:10px 15px;border:1px solid #e8e0d5;border-radius:8px;background:white;outline:none;"
                            onfocus="this.style.borderColor='#2d5a27'" onblur="this.style.borderColor='#e8e0d5'">
                            <option value="">Tous les statuts</option>
                            <option value="brouillon"    {{ request('statut') == 'brouillon'    ? 'selected' : '' }}>Brouillon</option>
                            <option value="confirme"     {{ request('statut') == 'confirme'     ? 'selected' : '' }}>Confirmé</option>
                            <option value="recu_partiel" {{ request('statut') == 'recu_partiel' ? 'selected' : '' }}>Reçu partiellement</option>
                            <option value="recu_total"   {{ request('statut') == 'recu_total'   ? 'selected' : '' }}>Reçu totalement</option>
                            <option value="annule"       {{ request('statut') == 'annule'       ? 'selected' : '' }}>Annulé</option>
                        </select>
                    </div>

                    <div style="flex:1;min-width:160px;">
                        <label style="display:block;font-weight:600;color:#2d5a27;font-size:0.9rem;margin-bottom:5px;">
                            <i class="fas fa-truck" style="margin-right:5px;"></i> Fournisseur
                        </label>
                        <select name="fournisseur_id" style="width:100%;padding:10px 15px;border:1px solid #e8e0d5;border-radius:8px;background:white;outline:none;"
                            onfocus="this.style.borderColor='#2d5a27'" onblur="this.style.borderColor='#e8e0d5'">
                            <option value="">Tous les fournisseurs</option>
                            @foreach($fournisseurs as $f)
                                <option value="{{ $f->id }}" {{ request('fournisseur_id') == $f->id ? 'selected' : '' }}>
                                    {{ $f->prenom }} {{ $f->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div style="flex:0 0 160px;">
                        <label style="display:block;font-weight:600;color:#2d5a27;font-size:0.9rem;margin-bottom:5px;">
                            <i class="fas fa-calendar" style="margin-right:5px;"></i> Du
                        </label>
                        <input type="date" name="du" value="{{ request('du') }}" style="width:100%;padding:10px 15px;border:1px solid #e8e0d5;border-radius:8px;background:white;outline:none;"
                            onfocus="this.style.borderColor='#2d5a27'" onblur="this.style.borderColor='#e8e0d5'">
                    </div>

                    <div style="flex:0 0 160px;">
                        <label style="display:block;font-weight:600;color:#2d5a27;font-size:0.9rem;margin-bottom:5px;">
                            <i class="fas fa-calendar" style="margin-right:5px;"></i> Au
                        </label>
                        <input type="date" name="au" value="{{ request('au') }}" style="width:100%;padding:10px 15px;border:1px solid #e8e0d5;border-radius:8px;background:white;outline:none;"
                            onfocus="this.style.borderColor='#2d5a27'" onblur="this.style.borderColor='#e8e0d5'">
                    </div>

                    <div style="flex:0 0 auto;display:flex;gap:10px;">
                        <button type="submit" style="background:#2d5a27;color:white;padding:10px 25px;border-radius:30px;border:none;font-weight:500;cursor:pointer;display:inline-flex;align-items:center;gap:8px;"
                            onmouseover="this.style.background='#1e3d1a'" onmouseout="this.style.background='#2d5a27'">
                            <i class="fas fa-filter"></i> Filtrer
                        </button>
                        <a href="{{ route('admin.achats.index') }}" style="background:#e8e0d5;color:#2d5a27;padding:10px 25px;border-radius:30px;text-decoration:none;font-weight:500;display:inline-flex;align-items:center;gap:8px;"
                            onmouseover="this.style.background='#d4c9bb'" onmouseout="this.style.background='#e8e0d5'">
                            <i class="fas fa-undo"></i> Réinitialiser
                        </a>
                    </div>

                </form>
            </div>

            {{-- Tableau --}}
            <div style="background:white;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.08);overflow:hidden;border:1px solid #e8e0d5;">
                <div style="overflow-x:auto;">
                    <table style="width:100%;border-collapse:collapse;font-size:0.95rem;">
                        <thead style="background:#f8f5f0;border-bottom:2px solid #e8e0d5;">
                            <tr>
                                <th style="padding:15px 20px;text-align:left;font-weight:600;color:#2d5a27;">Numéro</th>
                                <th style="padding:15px 20px;text-align:left;font-weight:600;color:#2d5a27;">Fournisseur</th>
                                <th style="padding:15px 20px;text-align:center;font-weight:600;color:#2d5a27;">Date achat</th>
                                <th style="padding:15px 20px;text-align:center;font-weight:600;color:#2d5a27;">Statut</th>
                                <th style="padding:15px 20px;text-align:right;font-weight:600;color:#2d5a27;">Montant</th>
                                <th style="padding:15px 20px;text-align:center;font-weight:600;color:#2d5a27;">Créé par</th>
                                <th style="padding:15px 20px;text-align:center;font-weight:600;color:#2d5a27;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($achats as $achat)
                                <tr style="border-bottom:1px solid #f0ebe5;transition:background 0.2s;"
                                    onmouseover="this.style.background='#fafaf8'" onmouseout="this.style.background='white'">

                                    <td style="padding:15px 20px;font-weight:600;color:#2d5a27;">
                                        {{ $achat->numero }}
                                    </td>

                                    <td style="padding:15px 20px;">
                                        <div style="display:flex;align-items:center;gap:10px;">
                                            <div style="width:36px;height:36px;border-radius:50%;background:#2d5a27;color:white;display:flex;align-items:center;justify-content:center;font-weight:bold;font-size:13px;flex-shrink:0;">
                                                {{ strtoupper(substr($achat->fournisseur->prenom ?? '', 0, 1)) }}{{ strtoupper(substr($achat->fournisseur->nom ?? '', 0, 1)) }}
                                            </div>
                                            <span style="font-weight:500;">{{ $achat->fournisseur->prenom }} {{ $achat->fournisseur->nom }}</span>
                                        </div>
                                    </td>

                                    <td style="padding:15px 20px;text-align:center;color:#6c757d;">
                                        {{ $achat->date_achat->format('d/m/Y') }}
                                    </td>

                                    <td style="padding:15px 20px;text-align:center;">
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
                                        <span style="background:{{ $badge['bg'] }};color:{{ $badge['color'] }};padding:4px 14px;border-radius:20px;font-size:0.8rem;font-weight:500;display:inline-flex;align-items:center;gap:6px;">
                                            <i class="fas {{ $badge['icon'] }}"></i> {{ $badge['label'] }}
                                        </span>
                                    </td>

                                    <td style="padding:15px 20px;text-align:right;font-weight:600;color:#2d5a27;">
                                        {{ number_format($achat->mt_total, 0, ',', ' ') }} FCFA
                                    </td>

                                    <td style="padding:15px 20px;text-align:center;color:#6c757d;font-size:0.9rem;">
                                        {{ $achat->user->prenom ?? '-' }}
                                    </td>

                                    <td style="padding:15px 20px;text-align:center;">
                                        <div style="display:flex;gap:6px;justify-content:center;">
                                            <a href="{{ route('admin.achats.show', $achat) }}" style="background:#f0ebe5;color:#2d5a27;padding:6px 12px;border-radius:20px;text-decoration:none;font-size:0.8rem;display:inline-flex;align-items:center;gap:4px;"
                                                onmouseover="this.style.background='#e0d6c8'" onmouseout="this.style.background='#f0ebe5'">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            @if($achat->statut === 'brouillon')
                                                <form action="{{ route('admin.achats.confirmer', $achat) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" style="background:#cce5ff;color:#004085;padding:6px 12px;border-radius:20px;border:none;font-size:0.8rem;cursor:pointer;display:inline-flex;align-items:center;gap:4px;"
                                                        onmouseover="this.style.background='#b8daff'" onmouseout="this.style.background='#cce5ff'">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            @if(in_array($achat->statut, ['confirme', 'recu_partiel']))
                                                <a href="{{ route('admin.achats.reception', $achat) }}" style="background:#d4edda;color:#155724;padding:6px 12px;border-radius:20px;text-decoration:none;font-size:0.8rem;display:inline-flex;align-items:center;gap:4px;"
                                                    onmouseover="this.style.background='#c3e6cb'" onmouseout="this.style.background='#d4edda'">
                                                    <i class="fas fa-truck-loading"></i>
                                                </a>
                                            @endif

                                            @if(in_array($achat->statut, ['brouillon', 'confirme']))
                                                <form action="{{ route('admin.achats.annuler', $achat) }}" method="POST" style="display:inline;" onsubmit="return confirm('Annuler cet achat ?');">
                                                    @csrf
                                                    <button type="submit" style="background:#f8d7da;color:#721c24;padding:6px 12px;border-radius:20px;border:none;font-size:0.8rem;cursor:pointer;display:inline-flex;align-items:center;gap:4px;"
                                                        onmouseover="this.style.background='#f5c6cb'" onmouseout="this.style.background='#f8d7da'">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" style="padding:60px 20px;text-align:center;color:#6c757d;">
                                        <i class="fas fa-shopping-basket" style="font-size:48px;display:block;margin-bottom:15px;color:#d4c9bb;"></i>
                                        <p style="font-size:1.1rem;margin:0;">Aucune commande d'achat trouvée</p>
                                        <p style="margin-top:5px;">Cliquez sur "Nouvelle commande" pour en créer une</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-4">

    <!-- Informations -->
    <div class="text-muted small">
        <i class="fas fa-info-circle text-success"></i>

        @if ($achats->total() > 0)
            Affichage de
            <strong>{{ $achats->firstItem() }}</strong>
            à
            <strong>{{ $achats->lastItem() }}</strong>
            sur
            <strong>{{ $achats->total() }}</strong>
            fournisseurs
        @else
            Aucun fournisseur trouvé
        @endif
    </div>

    <!-- Pagination Bootstrap 5 -->
    @if ($achats->hasPages())
        <nav aria-label="Pagination des fournisseurs">
            <ul class="pagination mb-0">

                {{-- Précédent --}}
                @if ($achats->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link"
                           href="{{ $achats->previousPageUrl() }}">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                @endif

                {{-- Pages --}}
                @foreach ($achats->getUrlRange(1, $achats->lastPage()) as $page => $url)
                    @if ($page == $achats->currentPage())
                        <li class="page-item active">
                            <span class="page-link">{{ $page }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link"
                               href="{{ $url }}">
                                {{ $page }}
                            </a>
                        </li>
                    @endif
                @endforeach

                {{-- Suivant --}}
                @if ($achats->hasMorePages())
                    <li class="page-item">
                        <a class="page-link"
                           href="{{ $achats->nextPageUrl() }}">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                    </li>
                @endif

            </ul>
        </nav>
    @endif

</div>

        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .pagination { display:flex;list-style:none;gap:5px;padding:0;margin:0; }
    .pagination li a, .pagination li span { display:inline-block;padding:8px 16px;background:white;border:1px solid #e8e0d5;border-radius:6px;color:#2d5a27;text-decoration:none;transition:all 0.2s;font-size:0.9rem; }
    .pagination li a:hover { background:#2d5a27;color:white;border-color:#2d5a27; }
    .pagination li.active span { background:#2d5a27;color:white;border-color:#2d5a27; }
    .pagination li.disabled span { color:#adb5bd;background:#f8f5f0;border-color:#e8e0d5; }
</style>
@endpush