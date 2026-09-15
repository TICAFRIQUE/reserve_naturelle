@extends('layouts.admin')

@section('title', 'Réception achat ' . $achat->numero . ' - La Réserve Naturelle')

@section('content')
<div class="container" style="padding: 40px 0;">
    <div class="row">
        <div class="col-12">

            {{-- Entête --}}
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:30px;flex-wrap:wrap;gap:15px;">
                <div>
                    <h1 style="font-family:'Playfair Display',serif;color:#2d5a27;font-size:2rem;margin:0;">
                        <i class="fas fa-truck-loading" style="margin-right:10px;"></i>
                        Réception — {{ $achat->numero }}
                    </h1>
                    <p style="color:#6c757d;margin:5px 0 0 0;">
                        Fournisseur : <strong>{{ $achat->fournisseur->prenom }} {{ $achat->fournisseur->nom }}</strong>
                    </p>
                </div>
                <a href="{{ route('admin.achats.show', $achat) }}" style="background:#e8e0d5;color:#2d5a27;padding:12px 24px;border-radius:30px;text-decoration:none;font-weight:500;display:inline-flex;align-items:center;gap:8px;"
                    onmouseover="this.style.background='#d4c9bb'" onmouseout="this.style.background='#e8e0d5'">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>

            {{-- Erreurs --}}
            @if($errors->any())
                <div style="background:#f8d7da;color:#721c24;padding:15px 20px;border-radius:8px;border-left:4px solid #dc3545;margin-bottom:20px;">
                    <i class="fas fa-exclamation-circle" style="margin-right:8px;"></i>
                    <strong>Veuillez corriger les erreurs suivantes :</strong>
                    <ul style="margin:10px 0 0 20px;padding:0;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.achats.receptionner', $achat) }}" method="POST">
                @csrf

                {{-- Infos réception --}}
                <div style="background:white;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.08);border:1px solid #e8e0d5;padding:25px;margin-bottom:25px;">
                    <h2 style="font-family:'Playfair Display',serif;color:#2d5a27;font-size:1.2rem;margin:0 0 20px 0;padding-bottom:15px;border-bottom:1px solid #e8e0d5;">
                        <i class="fas fa-info-circle" style="margin-right:8px;"></i> Informations de réception
                    </h2>
                    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px;">

                        <div>
                            <label style="display:block;font-weight:600;color:#2d5a27;font-size:0.9rem;margin-bottom:6px;">
                                Date de réception <span style="color:#dc3545;">*</span>
                            </label>
                            <input type="date" name="date_reception" required value="{{ old('date_reception', date('Y-m-d')) }}"
                                style="width:100%;padding:10px 15px;border:1px solid #e8e0d5;border-radius:8px;background:white;outline:none;box-sizing:border-box;"
                                onfocus="this.style.borderColor='#2d5a27'" onblur="this.style.borderColor='#e8e0d5'">
                        </div>

                        <div>
                            <label style="display:block;font-weight:600;color:#2d5a27;font-size:0.9rem;margin-bottom:6px;">
                                Montant payé (FCFA) <span style="color:#dc3545;">*</span>
                            </label>
                            <input type="number" name="mt_paye" required min="0" value="{{ old('mt_paye', $achat->mt_total) }}"
                                style="width:100%;padding:10px 15px;border:1px solid #e8e0d5;border-radius:8px;background:white;outline:none;box-sizing:border-box;"
                                onfocus="this.style.borderColor='#2d5a27'" onblur="this.style.borderColor='#e8e0d5'">
                        </div>

                    </div>
                </div>

                {{-- Lignes à réceptionner --}}
                <div style="background:white;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.08);border:1px solid #e8e0d5;padding:25px;margin-bottom:25px;">
                    <h2 style="font-family:'Playfair Display',serif;color:#2d5a27;font-size:1.2rem;margin:0 0 20px 0;padding-bottom:15px;border-bottom:1px solid #e8e0d5;">
                        <i class="fas fa-boxes" style="margin-right:8px;"></i> Produits à réceptionner
                    </h2>

                    {{-- Légende --}}
                    <div style="display:flex;gap:15px;flex-wrap:wrap;margin-bottom:20px;">
                        <span style="background:#d4edda;color:#155724;padding:4px 14px;border-radius:20px;font-size:0.8rem;font-weight:500;">
                            <i class="fas fa-check-double"></i> Totalement reçu
                        </span>
                        <span style="background:#fff3cd;color:#856404;padding:4px 14px;border-radius:20px;font-size:0.8rem;font-weight:500;">
                            <i class="fas fa-clock"></i> Partiellement reçu
        				</span>
                        <span style="background:#f8f5f0;color:#6c757d;padding:4px 14px;border-radius:20px;font-size:0.8rem;font-weight:500;">
                            <i class="fas fa-hourglass"></i> En attente
                        </span>
                    </div>

                    <div style="overflow-x:auto;">
                        <table style="width:100%;border-collapse:collapse;font-size:0.95rem;">
                            <thead style="background:#f8f5f0;border-bottom:2px solid #e8e0d5;">
                                <tr>
                                    <th style="padding:12px 20px;text-align:left;font-weight:600;color:#2d5a27;">Produit</th>
                                    <th style="padding:12px 20px;text-align:center;font-weight:600;color:#2d5a27;">Stock actuel</th>
                                    <th style="padding:12px 20px;text-align:center;font-weight:600;color:#2d5a27;">Commandé</th>
                                    <th style="padding:12px 20px;text-align:center;font-weight:600;color:#2d5a27;">Déjà reçu</th>
                                    <th style="padding:12px 20px;text-align:center;font-weight:600;color:#2d5a27;">Reliquat</th>
                                    <th style="padding:12px 20px;text-align:center;font-weight:600;color:#2d5a27;">Qté reçue aujourd'hui <span style="color:#dc3545;">*</span></th>
                                    <th style="padding:12px 20px;text-align:center;font-weight:600;color:#2d5a27;">État</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($achat->produits as $index => $ligne)
                                     <input type="hidden" name="lignes[{{ $index }}][achat_product_id]" value="{{ old('lignes.' . $index . '.achat_product_id', $ligne->id) }}">
                                    @php
                                        $reliquat = $ligne->reliquat;
                                        $complet  = $reliquat <= 0;
                                    @endphp
                                    <tr style="border-bottom:1px solid #f0ebe5;background:{{ $complet ? '#f8fff8' : 'white' }};">
                                        <td style="padding:12px 20px;font-weight:500;color:#2d5a27;">
                                            {{ $ligne->product->designation }}
                                            <div style="font-size:0.8rem;color:#6c757d;">{{ $ligne->product->reference_prod }}</div>
                                        </td>
                                        <td style="padding:12px 20px;text-align:center;color:#6c757d;">
                                            {{ $ligne->product->qte_dispo }}
                                        </td>
                                        <td style="padding:12px 20px;text-align:center;font-weight:600;">
                                            {{ $ligne->qte_commandee }}
                                        </td>
                                        <td style="padding:12px 20px;text-align:center;color:#155724;font-weight:600;">
                                            {{ $ligne->qte_recue }}
                                        </td>
                                        <td style="padding:12px 20px;text-align:center;">
                                            @if($complet)
                                                <span style="background:#d4edda;color:#155724;padding:3px 10px;border-radius:20px;font-size:0.85rem;">
                                                    <i class="fas fa-check"></i> Complet
                                                </span>
                                            @else
                                                <span style="background:#fff3cd;color:#856404;padding:3px 10px;border-radius:20px;font-size:0.85rem;font-weight:600;">
                                                    {{ $reliquat }}
                                                </span>
                                            @endif
                                        </td>
                                        <td style="padding:12px 20px;text-align:center;">
                                 <input type="number"
    name="lignes[{{ $index }}][qte_recue]"
    min="0"
    max="{{ $reliquat }}"
    value="{{ old('lignes.' . $index . '.qte_recue', $complet ? 0 : '') }}"
    placeholder="0"
    {{ $complet ? 'disabled' : '' }}
    style="width:90px;padding:8px 12px;border:1px solid {{ $complet ? '#e8e0d5' : '#2d5a27' }};
    border-radius:8px;text-align:center;background:{{ $complet ? '#f8f5f0' : 'white' }};
    outline:none;">
                                        </td>
                                        <td style="padding:12px 20px;text-align:center;">
                                            @if($complet)
                                                <span style="background:#d4edda;color:#155724;padding:4px 14px;border-radius:20px;font-size:0.8rem;font-weight:500;">
                                                    <i class="fas fa-check-double"></i> Totalement reçu
                                                </span>
                                            @elseif($ligne->qte_recue > 0)
                                                <span style="background:#fff3cd;color:#856404;padding:4px 14px;border-radius:20px;font-size:0.8rem;font-weight:500;">
                                                    <i class="fas fa-clock"></i> Partiel
                                                </span>
                                            @else
                                                <span style="background:#f8f5f0;color:#6c757d;padding:4px 14px;border-radius:20px;font-size:0.8rem;font-weight:500;">
                                                    <i class="fas fa-hourglass"></i> En attente
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Actions --}}
                <div style="display:flex;gap:15px;justify-content:flex-end;flex-wrap:wrap;">
                    <a href="{{ route('admin.achats.show', $achat) }}" style="background:#e8e0d5;color:#2d5a27;padding:12px 28px;border-radius:30px;text-decoration:none;font-weight:500;display:inline-flex;align-items:center;gap:8px;"
                        onmouseover="this.style.background='#d4c9bb'" onmouseout="this.style.background='#e8e0d5'">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                    <button type="submit" style="background:#2d5a27;color:white;padding:12px 28px;border-radius:30px;border:none;font-weight:500;cursor:pointer;display:inline-flex;align-items:center;gap:8px;"
                        onmouseover="this.style.background='#1e3d1a'" onmouseout="this.style.background='#2d5a27'">
                        <i class="fas fa-check-circle"></i> Valider la réception
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection