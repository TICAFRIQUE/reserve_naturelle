@extends('layouts.admin')

@section('title', 'Nouvelle commande d\'achat - La Réserve Naturelle')

@section('content')
<div class="container" style="padding: 40px 0;">
    <div class="row">
        <div class="col-12">

            {{-- Entête --}}
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:30px;flex-wrap:wrap;gap:15px;">
                <div>
                    <h1 style="font-family:'Playfair Display',serif;color:#2d5a27;font-size:2rem;margin:0;">
                        <i class="fas fa-plus-circle" style="margin-right:10px;"></i>
                        Nouvelle commande d'achat
                    </h1>
                    <p style="color:#6c757d;margin:5px 0 0 0;">Créez un bon de commande fournisseur</p>
                </div>
                <a href="{{ route('admin.achats.index') }}" style="background:#e8e0d5;color:#2d5a27;padding:12px 24px;border-radius:30px;text-decoration:none;font-weight:500;display:inline-flex;align-items:center;gap:8px;"
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

            <form action="{{ route('admin.achats.store') }}" method="POST" id="formAchat">
                @csrf

                {{-- Infos générales --}}
                <div style="background:white;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.08);border:1px solid #e8e0d5;padding:25px;margin-bottom:25px;">
                    <h2 style="font-family:'Playfair Display',serif;color:#2d5a27;font-size:1.3rem;margin:0 0 20px 0;padding-bottom:15px;border-bottom:1px solid #e8e0d5;">
                        <i class="fas fa-info-circle" style="margin-right:8px;"></i> Informations générales
                    </h2>

                    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px;">

                        <div>
                            <label style="display:block;font-weight:600;color:#2d5a27;font-size:0.9rem;margin-bottom:6px;">
                                Numéro <span style="color:#6c757d;font-weight:400;">(généré automatiquement)</span>
                            </label>
                            <input type="text" disabled placeholder="ACH-2026-08-05-001" style="width:100%;padding:10px 15px;border:1px solid #e8e0d5;border-radius:8px;background:#f8f5f0;color:#6c757d;box-sizing:border-box;">
                        </div>

                        <div>
                            <label style="display:block;font-weight:600;color:#2d5a27;font-size:0.9rem;margin-bottom:6px;">
                                Fournisseur <span style="color:#dc3545;">*</span>
                            </label>
                            <select name="fournisseur_id" required style="width:100%;padding:10px 15px;border:1px solid #e8e0d5;border-radius:8px;background:white;outline:none;box-sizing:border-box;"
                                onfocus="this.style.borderColor='#2d5a27'" onblur="this.style.borderColor='#e8e0d5'">
                                <option value="">— Choisir —</option>
                                @foreach($fournisseurs as $f)
                                    <option value="{{ $f->id }}" {{ old('fournisseur_id') == $f->id ? 'selected' : '' }}>
                                        {{ $f->prenom }} {{ $f->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label style="display:block;font-weight:600;color:#2d5a27;font-size:0.9rem;margin-bottom:6px;">
                                Date d'achat <span style="color:#dc3545;">*</span>
                            </label>
                            <input type="date" name="date_achat" required value="{{ old('date_achat', date('Y-m-d')) }}" style="width:100%;padding:10px 15px;border:1px solid #e8e0d5;border-radius:8px;background:white;outline:none;box-sizing:border-box;"
                                onfocus="this.style.borderColor='#2d5a27'" onblur="this.style.borderColor='#e8e0d5'">
                        </div>

                        <div>
                            <label style="display:block;font-weight:600;color:#2d5a27;font-size:0.9rem;margin-bottom:6px;">
                                Réception prévue
                            </label>
                            <input type="date" name="date_reception_prevue" value="{{ old('date_reception_prevue') }}" style="width:100%;padding:10px 15px;border:1px solid #e8e0d5;border-radius:8px;background:white;outline:none;box-sizing:border-box;"
                                onfocus="this.style.borderColor='#2d5a27'" onblur="this.style.borderColor='#e8e0d5'">
                        </div>

                        <div style="grid-column:1/-1;">
                            <label style="display:block;font-weight:600;color:#2d5a27;font-size:0.9rem;margin-bottom:6px;">
                                Notes
                            </label>
                            <textarea name="notes" rows="2" placeholder="Remarques, conditions particulières..." style="width:100%;padding:10px 15px;border:1px solid #e8e0d5;border-radius:8px;background:white;outline:none;resize:vertical;box-sizing:border-box;"
                                onfocus="this.style.borderColor='#2d5a27'" onblur="this.style.borderColor='#e8e0d5'">{{ old('notes') }}</textarea>
                        </div>

                    </div>
                </div>

                {{-- Lignes produits --}}
                <div style="background:white;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.08);border:1px solid #e8e0d5;padding:25px;margin-bottom:25px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;padding-bottom:15px;border-bottom:1px solid #e8e0d5;">
                        <h2 style="font-family:'Playfair Display',serif;color:#2d5a27;font-size:1.3rem;margin:0;">
                            <i class="fas fa-list" style="margin-right:8px;"></i> Lignes de la commande
                        </h2>
                        <button type="button" id="btnAjouterLigne" style="background:#2d5a27;color:white;padding:8px 20px;border-radius:30px;border:none;font-weight:500;cursor:pointer;display:inline-flex;align-items:center;gap:8px;"
                            onmouseover="this.style.background='#1e3d1a'" onmouseout="this.style.background='#2d5a27'">
                            <i class="fas fa-plus"></i> Ajouter une ligne
                        </button>
                    </div>

                    <div id="lignesContainer">
                        {{-- Ligne initiale --}}
                        <div class="ligne-achat" style="display:grid;grid-template-columns:2fr 1fr 1fr auto;gap:15px;align-items:end;padding:15px;background:#f8f5f0;border-radius:8px;margin-bottom:12px;">
                            <div>
                                <label style="display:block;font-weight:600;color:#2d5a27;font-size:0.85rem;margin-bottom:5px;">
                                    Produit <span style="color:#dc3545;">*</span>
                                </label>
                                <select name="lignes[0][product_id]" required class="select-produit" style="width:100%;padding:10px 15px;border:1px solid #e8e0d5;border-radius:8px;background:white;outline:none;"
                                    onfocus="this.style.borderColor='#2d5a27'" onblur="this.style.borderColor='#e8e0d5'">
                                    <option value="">— Choisir —</option>
                                    @foreach($produits as $p)
                                        <option value="{{ $p->id }}" data-stock="{{ $p->qte_dispo }}" data-cmp="{{ $p->cmp }}">
                                            {{ $p->designation }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label style="display:block;font-weight:600;color:#2d5a27;font-size:0.85rem;margin-bottom:5px;">
                                    Quantité <span style="color:#dc3545;">*</span>
                                </label>
                                <input type="number" name="lignes[0][qte_commandee]" min="1" required placeholder="0" class="input-qte" style="width:100%;padding:10px 15px;border:1px solid #e8e0d5;border-radius:8px;background:white;outline:none;box-sizing:border-box;"
                                    onfocus="this.style.borderColor='#2d5a27'" onblur="this.style.borderColor='#e8e0d5'">
                            </div>
                            <div>
                                <label style="display:block;font-weight:600;color:#2d5a27;font-size:0.85rem;margin-bottom:5px;">
                                    Prix unitaire (FCFA) <span style="color:#dc3545;">*</span>
                                </label>
                                <input type="number" name="lignes[0][prix_unitaire]" min="0" required placeholder="0" class="input-prix" style="width:100%;padding:10px 15px;border:1px solid #e8e0d5;border-radius:8px;background:white;outline:none;box-sizing:border-box;"
                                    onfocus="this.style.borderColor='#2d5a27'" onblur="this.style.borderColor='#e8e0d5'">
                            </div>
                            <div style="display:flex;flex-direction:column;gap:5px;">
                                <label style="display:block;font-weight:600;color:#2d5a27;font-size:0.85rem;margin-bottom:5px;">
                                    Sous-total
                                </label>
                                <span class="sous-total" style="padding:10px 15px;background:#e8f5e9;border-radius:8px;font-weight:600;color:#2d5a27;white-space:nowrap;">0 FCFA</span>
                            </div>
                            <div style="padding-bottom:2px;">
                                <button type="button" class="btn-suppr-ligne" style="background:#f8d7da;color:#721c24;padding:10px 12px;border-radius:8px;border:none;cursor:pointer;" title="Supprimer">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Total --}}
                    <div style="display:flex;justify-content:flex-end;margin-top:15px;padding-top:15px;border-top:2px solid #e8e0d5;">
                        <div style="background:#2d5a27;color:white;padding:12px 25px;border-radius:8px;font-weight:700;font-size:1.1rem;">
                            Total : <span id="totalGeneral">0</span> FCFA
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div style="display:flex;gap:15px;justify-content:flex-end;flex-wrap:wrap;">
                    <a href="{{ route('admin.achats.index') }}" style="background:#e8e0d5;color:#2d5a27;padding:12px 28px;border-radius:30px;text-decoration:none;font-weight:500;display:inline-flex;align-items:center;gap:8px;"
                        onmouseover="this.style.background='#d4c9bb'" onmouseout="this.style.background='#e8e0d5'">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                    <button type="submit" name="action" value="brouillon" style="background:#6c757d;color:white;padding:12px 28px;border-radius:30px;border:none;font-weight:500;cursor:pointer;display:inline-flex;align-items:center;gap:8px;"
                        onmouseover="this.style.background='#5a6268'" onmouseout="this.style.background='#6c757d'">
                        <i class="fas fa-save"></i> Enregistrer en brouillon
                    </button>
                    <button type="submit" name="action" value="confirmer" style="background:#2d5a27;color:white;padding:12px 28px;border-radius:30px;border:none;font-weight:500;cursor:pointer;display:inline-flex;align-items:center;gap:8px;"
                        onmouseover="this.style.background='#1e3d1a'" onmouseout="this.style.background='#2d5a27'">
                        <i class="fas fa-check-circle"></i> Valider la commande
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="{{ asset('js/admin/achat-create.js') }}"></script>
<script>
    const produits = {{ Js::from($produits->map(function($p) {
        return [
            'id'          => $p->id,
            'designation' => $p->designation,
            'stock'       => $p->qte_dispo,
            'cmp'         => $p->cmp,
        ];
    })) }};

    document.addEventListener('DOMContentLoaded', function() {
        initAchatCreate(produits);
    });
</script>
@endpush