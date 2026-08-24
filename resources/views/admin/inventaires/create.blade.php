{{-- resources/views/admin/inventaires/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Nouvel Inventaire - La Réserve Naturelle')

@section('content')
<div class="container" style="padding: 40px 0;">
    <div class="row">
        <div class="col-12">
            <!-- Entête avec titre et bouton retour -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h1 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 2rem; margin: 0;">
                        <i class="fas fa-plus-circle" style="color: #b8860b; margin-right: 10px;"></i>
                        Nouvel inventaire
                    </h1>
                    <p style="color: #6c757d; margin: 5px 0 0 0;">Créez un nouvel inventaire pour votre stock</p>
                </div>
                <a href="{{ route('admin.inventaires.index') }}" style="
                    background: #e8e0d5;
                    color: #2d5a27;
                    padding: 10px 24px;
                    border-radius: 30px;
                    text-decoration: none;
                    font-weight: 500;
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                    transition: all 0.3s ease;
                " onmouseover="this.style.background='#d4c9bb'" onmouseout="this.style.background='#e8e0d5'">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>

            <!-- Messages d'erreur -->
            @if ($errors->any())
                <div style="
                    background: #fce4ec;
                    padding: 16px 20px;
                    border-radius: 8px;
                    border-left: 4px solid #c62828;
                    margin-bottom: 20px;
                ">
                    <div style="display: flex; align-items: flex-start; gap: 10px;">
                        <i class="fas fa-exclamation-circle" style="color: #c62828; font-size: 20px; margin-top: 2px;"></i>
                        <div>
                            <strong style="color: #c62828; display: block; margin-bottom: 5px;">Veuillez corriger les erreurs suivantes :</strong>
                            <ul style="margin: 0; padding-left: 20px; color: #c62828; font-size: 14px;">
                                @foreach ($errors->all() as $error)
                                    <li style="margin-bottom: 3px;">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Formulaire -->
            <div style="
                background: white;
                border-radius: 12px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                padding: 30px;
                border: 1px solid #e8e0d5;
            ">
                <form method="POST" action="{{ route('admin.inventaires.store') }}">
                    @csrf

                    <!-- Date -->
                    <div style="margin-bottom: 25px;">
                        <label for="date_inventaire" style="
                            display: block; 
                            font-weight: 600; 
                            color: #2d5a27; 
                            font-size: 0.95rem; 
                            margin-bottom: 6px;
                        ">
                            <i class="fas fa-calendar-alt" style="color: #b8860b; margin-right: 8px;"></i>
                            Date d'inventaire <span style="color: #dc3545;">*</span>
                        </label>
                        <input type="date" 
                               id="date_inventaire" 
                               name="date_inventaire"
                               value="{{ old('date_inventaire', now()->format('Y-m-d')) }}" 
                               required
                               style="
                                   width: 100%;
                                   max-width: 300px;
                                   padding: 12px 16px;
                                   border: 2px solid #e8e0d5;
                                   border-radius: 8px;
                                   font-size: 0.95rem;
                                   transition: border-color 0.3s;
                                   outline: none;
                                   background: white;
                               "
                               onfocus="this.style.borderColor='#2d5a27'"
                               onblur="this.style.borderColor='#e8e0d5'">
                        @error('date_inventaire')
                            <div style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div style="margin-bottom: 25px;">
                        <label for="notes" style="
                            display: block; 
                            font-weight: 600; 
                            color: #2d5a27; 
                            font-size: 0.95rem; 
                            margin-bottom: 6px;
                        ">
                            <i class="fas fa-sticky-note" style="color: #b8860b; margin-right: 8px;"></i>
                            Notes
                        </label>
                        <textarea id="notes" 
                                  name="notes" 
                                  rows="3"
                                  placeholder="Ajoutez des notes ou commentaires sur cet inventaire..."
                                  style="
                                      width: 100%;
                                      padding: 12px 16px;
                                      border: 2px solid #e8e0d5;
                                      border-radius: 8px;
                                      font-size: 0.95rem;
                                      transition: border-color 0.3s;
                                      outline: none;
                                      font-family: inherit;
                                      resize: vertical;
                                  "
                                  onfocus="this.style.borderColor='#2d5a27'"
                                  onblur="this.style.borderColor='#e8e0d5'">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Type d'inventaire -->
                    <div style="margin-bottom: 25px;">
                        <label style="
                            display: block; 
                            font-weight: 600; 
                            color: #2d5a27; 
                            font-size: 0.95rem; 
                            margin-bottom: 10px;
                        ">
                            <i class="fas fa-tasks" style="color: #b8860b; margin-right: 8px;"></i>
                            Type d'inventaire <span style="color: #dc3545;">*</span>
                        </label>
                        <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                            <label style="
                                display: flex;
                                align-items: center;
                                gap: 8px;
                                padding: 10px 20px;
                                border: 2px solid #e8e0d5;
                                border-radius: 8px;
                                cursor: pointer;
                                transition: all 0.3s ease;
                                background: white;
                            " onmouseover="this.style.borderColor='#2d5a27'" onmouseout="this.style.borderColor='#e8e0d5'">
                                <input type="radio" 
                                       name="scope" 
                                       value="global" 
                                       checked 
                                       onchange="toggleScope()"
                                       style="accent-color: #2d5a27; width: 16px; height: 16px;">
                                <span style="font-weight: 500; color: #2d5a27;">
                                    <i class="fas fa-globe" style="color: #b8860b;"></i> Global (tous les produits)
                                </span>
                            </label>
                            <label style="
                                display: flex;
                                align-items: center;
                                gap: 8px;
                                padding: 10px 20px;
                                border: 2px solid #e8e0d5;
                                border-radius: 8px;
                                cursor: pointer;
                                transition: all 0.3s ease;
                                background: white;
                            " onmouseover="this.style.borderColor='#2d5a27'" onmouseout="this.style.borderColor='#e8e0d5'">
                                <input type="radio" 
                                       name="scope" 
                                       value="partiel" 
                                       onchange="toggleScope()"
                                       style="accent-color: #2d5a27; width: 16px; height: 16px;">
                                <span style="font-weight: 500; color: #2d5a27;">
                                    <i class="fas fa-list" style="color: #b8860b;"></i> Partiel (sélection)
                                </span>
                            </label>
                        </div>
                    </div>

                    <!-- Sélection des produits (partiel) -->
                    <div id="product-selector" style="display:none; margin-bottom: 25px;">
                        <div style="
                            background: #f8f5f0;
                            padding: 20px;
                            border-radius: 8px;
                            border: 1px solid #e8e0d5;
                        ">
                            <label style="
                                display: block; 
                                font-weight: 600; 
                                color: #2d5a27; 
                                font-size: 0.95rem; 
                                margin-bottom: 10px;
                            ">
                                <i class="fas fa-boxes" style="color: #b8860b; margin-right: 8px;"></i>
                                Sélectionner les produits <span style="color: #dc3545;">*</span>
                            </label>

                            <!-- Recherche -->
                            <div style="margin-bottom: 12px;">
                                <input type="text" 
                                       id="product-search" 
                                       placeholder="🔍 Rechercher un produit par nom ou référence..."
                                       style="
                                           width: 100%;
                                           padding: 10px 16px;
                                           border: 2px solid #e8e0d5;
                                           border-radius: 8px;
                                           font-size: 0.95rem;
                                           transition: border-color 0.3s;
                                           outline: none;
                                       "
                                       onfocus="this.style.borderColor='#2d5a27'"
                                       onblur="this.style.borderColor='#e8e0d5'">
                            </div>

                            <!-- Sélection rapide -->
                            <div style="display: flex; gap: 10px; margin-bottom: 12px; flex-wrap: wrap; align-items: center;">
                                <button type="button" onclick="selectAllProducts()" style="
                                    background: #2d5a27;
                                    color: white;
                                    padding: 6px 18px;
                                    border: none;
                                    border-radius: 20px;
                                    font-size: 0.8rem;
                                    cursor: pointer;
                                    transition: all 0.3s ease;
                                " onmouseover="this.style.background='#1e3d1a'" onmouseout="this.style.background='#2d5a27'">
                                    <i class="fas fa-check-double"></i> Tout sélectionner
                                </button>
                                <button type="button" onclick="unselectAllProducts()" style="
                                    background: #e8e0d5;
                                    color: #2d5a27;
                                    padding: 6px 18px;
                                    border: none;
                                    border-radius: 20px;
                                    font-size: 0.8rem;
                                    cursor: pointer;
                                    transition: all 0.3s ease;
                                " onmouseover="this.style.background='#d4c9bb'" onmouseout="this.style.background='#e8e0d5'">
                                    <i class="fas fa-times"></i> Tout désélectionner
                                </button>
                                <span style="
                                    font-size: 0.8rem;
                                    color: #6c757d;
                                    display: flex;
                                    align-items: center;
                                    margin-left: auto;
                                ">
                                    <span id="selectedCount">0</span> produit(s) sélectionné(s)
                                </span>
                            </div>

                            <!-- Liste des produits -->
                            <div class="product-list" style="
                                max-height: 400px; 
                                overflow-y: auto; 
                                border: 1px solid #e8e0d5; 
                                border-radius: 8px;
                                background: white;
                                padding: 10px;
                            ">
                                @forelse ($produits as $produit)
                                    <label class="product-row" 
                                           data-name="{{ strtolower($produit->designation ?? '') }}"
                                           data-ref="{{ strtolower($produit->reference_prod ?? '') }}"
                                           style="
                                               display: flex;
                                               align-items: center;
                                               gap: 10px;
                                               padding: 8px 12px;
                                               border-bottom: 1px solid #f0ebe5;
                                               transition: background 0.2s;
                                               cursor: pointer;
                                           "
                                           onmouseover="this.style.background='#faf8f5'"
                                           onmouseout="this.style.background='transparent'">
                                        <input type="checkbox" 
                                               name="product_ids[]" 
                                               value="{{ $produit->id }}"
                                               onchange="updateSelectedCount()"
                                               style="accent-color: #2d5a27; width: 16px; height: 16px;">
                                        <div style="flex: 1;">
                                            <div style="font-weight: 500; color: #2d5a27;">{{ $produit->designation ?? 'Sans nom' }}</div>
                                            <div style="font-size: 0.8rem; color: #6c757d;">
                                                <span style="
                                                    background: #e8f5e9;
                                                    padding: 1px 10px;
                                                    border-radius: 12px;
                                                    font-size: 0.7rem;
                                                    font-weight: 600;
                                                    color: #2d5a27;
                                                ">
                                                    {{ $produit->reference_prod ?? 'N/A' }}
                                                </span>
                                                <span style="margin-left: 8px;">
                                                    <i class="fas fa-cube" style="color: #b8860b;"></i>
                                                    Stock: {{ $produit->qte_dispo ?? 0 }}
                                                </span>
                                            </div>
                                        </div>
                                    </label>
                                @empty
                                    <div style="padding: 20px; text-align: center; color: #6c757d;">
                                        <i class="fas fa-box-open" style="font-size: 24px; display: block; margin-bottom: 10px;"></i>
                                        <p>Aucun produit disponible</p>
                                    </div>
                                @endforelse
                            </div>
                            @error('product_ids')
                                <div style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Boutons -->
                    <div style="display: flex; gap: 15px; flex-wrap: wrap; padding-top: 10px; border-top: 1px solid #e8e0d5;">
                        <button type="submit" style="
                            background: linear-gradient(135deg, #2d5a27 0%, #4caf50 100%);
                            color: white;
                            padding: 14px 35px;
                            border: none;
                            border-radius: 30px;
                            font-size: 1rem;
                            font-weight: 600;
                            cursor: pointer;
                            transition: all 0.3s ease;
                            display: inline-flex;
                            align-items: center;
                            gap: 10px;
                            box-shadow: 0 4px 15px rgba(45,90,39,0.25);
                        " onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 25px rgba(45,90,39,0.35)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(45,90,39,0.25)'">
                            <i class="fas fa-save"></i> Créer l'inventaire
                        </button>
                        <a href="{{ route('admin.inventaires.index') }}" style="
                            background: #e8e0d5;
                            color: #2d5a27;
                            padding: 14px 30px;
                            border-radius: 30px;
                            text-decoration: none;
                            font-weight: 500;
                            transition: all 0.3s ease;
                            display: inline-flex;
                            align-items: center;
                            gap: 8px;
                        " onmouseover="this.style.background='#d4c9bb'" onmouseout="this.style.background='#e8e0d5'">
                            <i class="fas fa-times"></i> Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function toggleScope() {
    const selected = document.querySelector('input[name="scope"]:checked');
    if (selected) {
        const isPartiel = selected.value === 'partiel';
        const selector = document.getElementById('product-selector');
        if (selector) {
            selector.style.display = isPartiel ? 'block' : 'none';
        }
    }
}

function updateSelectedCount() {
    const checkboxes = document.querySelectorAll('input[name="product_ids[]"]:checked');
    const countSpan = document.getElementById('selectedCount');
    if (countSpan) {
        countSpan.textContent = checkboxes.length;
    }
}

function selectAllProducts() {
    document.querySelectorAll('input[name="product_ids[]"]').forEach(cb => cb.checked = true);
    updateSelectedCount();
}

function unselectAllProducts() {
    document.querySelectorAll('input[name="product_ids[]"]').forEach(cb => cb.checked = false);
    updateSelectedCount();
}

// Recherche de produits
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('product-search');
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const term = e.target.value.toLowerCase();
            document.querySelectorAll('.product-row').forEach(row => {
                const name = row.dataset.name || '';
                const ref = row.dataset.ref || '';
                const match = name.includes(term) || ref.includes(term);
                row.style.display = match ? 'flex' : 'none';
            });
        });
    }
    
    // Mise à jour du compteur au chargement
    updateSelectedCount();
    
    // Initialisation de l'affichage du sélecteur
    toggleScope();
});
</script>
@endsection

@push('styles')
<style>
    /* Style de la scrollbar */
    .product-list::-webkit-scrollbar {
        width: 6px;
    }
    .product-list::-webkit-scrollbar-track {
        background: #f8f5f0;
        border-radius: 8px;
    }
    .product-list::-webkit-scrollbar-thumb {
        background: #b8860b;
        border-radius: 8px;
    }
    .product-list::-webkit-scrollbar-thumb:hover {
        background: #9a7209;
    }

    /* Animation pour le formulaire */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .container > div > div:last-child {
        animation: fadeIn 0.4s ease;
    }

    /* Correction pour éviter les débordements */
    * {
        box-sizing: border-box;
    }
    
    .container {
        max-width: 100% !important;
        overflow-x: hidden !important;
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
        .container > div > div:first-child > div:last-child a {
            width: 100%;
            justify-content: center;
        }
        .container > div > div:last-child {
            padding: 20px !important;
        }
        .container > div > div:last-child form > div:first-child input {
            max-width: 100% !important;
        }
        .container > div > div:last-child form > div:nth-child(3) > div {
            flex-direction: column !important;
        }
        .container > div > div:last-child form > div:last-child {
            flex-direction: column !important;
        }
        .container > div > div:last-child form > div:last-child button,
        .container > div > div:last-child form > div:last-child a {
            width: 100%;
            justify-content: center;
        }
        .product-row {
            flex-wrap: wrap !important;
        }
        .product-row > div:last-child {
            margin-left: 26px !important;
        }
    }
</style>
@endpush