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
                            <input type="text" disabled placeholder="ACH-2026-09-03-001" style="width:100%;padding:10px 15px;border:1px solid #e8e0d5;border-radius:8px;background:#f8f5f0;color:#6c757d;box-sizing:border-box;">
                        </div>

                        <div>
                            <label style="display:block;font-weight:600;color:#2d5a27;font-size:0.9rem;margin-bottom:6px;">
                                Fournisseur <span style="color:#dc3545;">*</span>
                            </label>
                            <select name="fournisseur_id" id="fournisseur_id" required style="width:100%;padding:10px 15px;border:1px solid #e8e0d5;border-radius:8px;background:white;outline:none;box-sizing:border-box;"
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
                            <input type="date" name="date_achat" id="date_achat" required value="{{ old('date_achat', date('Y-m-d')) }}" style="width:100%;padding:10px 15px;border:1px solid #e8e0d5;border-radius:8px;background:white;outline:none;box-sizing:border-box;"
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
                    <a href="{{ route('admin.achats.index') }}" class="btn-annuler" style="background:#e8e0d5;color:#2d5a27;padding:12px 28px;border-radius:30px;text-decoration:none;font-weight:500;display:inline-flex;align-items:center;gap:8px;"
                        onmouseover="this.style.background='#d4c9bb'" onmouseout="this.style.background='#e8e0d5'">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                    {{-- <button type="submit" name="action" value="brouillon" id="btnBrouillon" style="background:#6c757d;color:white;padding:12px 28px;border-radius:30px;border:none;font-weight:500;cursor:pointer;display:inline-flex;align-items:center;gap:8px;"
                        onmouseover="this.style.background='#5a6268'" onmouseout="this.style.background='#6c757d'">
                        <i class="fas fa-save"></i> Enregistrer en brouillon
                    </button> --}}
                    <button type="button" id="btnConfirmer" style="background:#2d5a27;color:white;padding:12px 28px;border-radius:30px;border:none;font-weight:500;cursor:pointer;display:inline-flex;align-items:center;gap:8px;"
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialisation des calculs
        function initCalculs() {
            const container = document.getElementById('lignesContainer');
            
            // Fonction pour calculer le sous-total d'une ligne
            function calculerSousTotal(ligne) {
                const qte = parseInt(ligne.querySelector('.input-qte').value) || 0;
                const prix = parseFloat(ligne.querySelector('.input-prix').value) || 0;
                const sousTotal = qte * prix;
                const span = ligne.querySelector('.sous-total');
                span.textContent = sousTotal + ' FCFA';
                return sousTotal;
            }

            // Fonction pour calculer le total général
            function calculerTotalGeneral() {
                const lignes = document.querySelectorAll('.ligne-achat');
                let total = 0;
                lignes.forEach(ligne => {
                    const qte = parseInt(ligne.querySelector('.input-qte').value) || 0;
                    const prix = parseFloat(ligne.querySelector('.input-prix').value) || 0;
                    total += qte * prix;
                });
                document.getElementById('totalGeneral').textContent = total.toLocaleString('fr-FR');
                return total;
            }

            // Événements sur les champs de quantité et prix
            container.addEventListener('input', function(e) {
                if (e.target.classList.contains('input-qte') || e.target.classList.contains('input-prix')) {
                    const ligne = e.target.closest('.ligne-achat');
                    if (ligne) {
                        calculerSousTotal(ligne);
                        calculerTotalGeneral();
                    }
                }
            });

            // Ajout d'une ligne
            document.getElementById('btnAjouterLigne').addEventListener('click', function() {
                const index = document.querySelectorAll('.ligne-achat').length;
                const template = document.querySelector('.ligne-achat').cloneNode(true);
                
                // Mise à jour des noms des champs
                template.querySelectorAll('select, input').forEach(el => {
                    const name = el.getAttribute('name');
                    if (name) {
                        el.setAttribute('name', name.replace(/\[\d+\]/, '[' + index + ']'));
                    }
                    if (el.tagName === 'SELECT') {
                        el.value = '';
                    } else if (el.type === 'number') {
                        el.value = '';
                    }
                });

                // Réinitialisation du sous-total
                const sousTotal = template.querySelector('.sous-total');
                if (sousTotal) sousTotal.textContent = '0 FCFA';

                // Ajout du bouton de suppression
                const btnSuppr = template.querySelector('.btn-suppr-ligne');
                if (btnSuppr) {
                    btnSuppr.addEventListener('click', function() {
                        const lignes = document.querySelectorAll('.ligne-achat');
                        if (lignes.length > 1) {
                            this.closest('.ligne-achat').remove();
                            calculerTotalGeneral();
                            // Réindexation des noms
                            document.querySelectorAll('.ligne-achat').forEach((l, i) => {
                                l.querySelectorAll('select, input').forEach(el => {
                                    const name = el.getAttribute('name');
                                    if (name) {
                                        el.setAttribute('name', name.replace(/\[\d+\]/, '[' + i + ']'));
                                    }
                                });
                            });
                        } else {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Impossible de supprimer',
                                text: 'La commande doit contenir au moins une ligne.',
                                confirmButtonColor: '#2d5a27',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }

                document.getElementById('lignesContainer').appendChild(template);
                
                // Recalcul du total
                calculerTotalGeneral();
            });

            // Gestion de la suppression des lignes existantes
            document.querySelectorAll('.btn-suppr-ligne').forEach(btn => {
                btn.addEventListener('click', function() {
                    const lignes = document.querySelectorAll('.ligne-achat');
                    if (lignes.length > 1) {
                        this.closest('.ligne-achat').remove();
                        calculerTotalGeneral();
                        // Réindexation des noms
                        document.querySelectorAll('.ligne-achat').forEach((l, i) => {
                            l.querySelectorAll('select, input').forEach(el => {
                                const name = el.getAttribute('name');
                                if (name) {
                                    el.setAttribute('name', name.replace(/\[\d+\]/, '[' + i + ']'));
                                }
                            });
                        });
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Impossible de supprimer',
                            text: 'La commande doit contenir au moins une ligne.',
                            confirmButtonColor: '#2d5a27',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });

            // Contrôle des doublons
            container.addEventListener('change', function(e) {
                if (!e.target.classList.contains('select-produit')) return;

                const select = e.target;
                const productId = select.value;
                if (!productId) return;

                const doublon = [...container.querySelectorAll('.select-produit')]
                    .some(s => s !== select && s.value === productId);

                if (doublon) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Produit déjà sélectionné',
                        text: 'Ce produit est déjà présent dans une autre ligne. Modifiez plutôt sa quantité.',
                        confirmButtonColor: '#2d5a27',
                        confirmButtonText: 'OK'
                    });
                    select.value = '';
                    select.dispatchEvent(new Event('change'));
                }
            });

            // Initialisation du calcul
            calculerTotalGeneral();
        }

        // Gestionnaire pour le bouton Valider
        function initValidation() {
            const btnConfirmer = document.getElementById('btnConfirmer');
            if (btnConfirmer) {
                btnConfirmer.addEventListener('click', function() {
                    // Vérifier qu'au moins une ligne est remplie
                    const selects = document.querySelectorAll('.select-produit');
                    let lignesRemplies = false;
                    let produitsValides = [];
                    
                    selects.forEach(select => {
                        if (select.value) {
                            lignesRemplies = true;
                            const option = select.options[select.selectedIndex];
                            produitsValides.push(option.text);
                        }
                    });

                    if (!lignesRemplies) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Commande vide',
                            text: 'Veuillez ajouter au moins un produit à la commande.',
                            confirmButtonColor: '#dc3545',
                            confirmButtonText: 'OK'
                        });
                        return;
                    }

                    // Récupérer les informations pour la confirmation
                    const fournisseurSelect = document.getElementById('fournisseur_id');
                    const fournisseurNom = fournisseurSelect.options[fournisseurSelect.selectedIndex]?.text || 'Non défini';
                    const total = document.getElementById('totalGeneral').textContent;
                    const nbLignes = document.querySelectorAll('.ligne-achat').length;

                    // Construire la liste des produits
                    let listeProduits = '';
                    produitsValides.forEach((nom, index) => {
                        listeProduits += `<p style="margin: 2px 0; font-size: 0.9rem;">${index + 1}. ${nom}</p>`;
                    });

                    Swal.fire({
                        title: '✅ Valider la commande ?',
                        html: `
                            <div style="text-align: left;">
                                <p style="color: #155724; font-weight: 500;">
                                    <i class="fas fa-exclamation-triangle" style="color: #856404;"></i>
                                    Vous êtes sur le point de valider cette commande.
                                </p>
                                <div style="background: #f8f5f0; padding: 12px; border-radius: 8px; margin: 10px 0;">
                                    <p style="margin: 5px 0; font-weight: 600; color: #2d5a27;">
                                        <i class="fas fa-truck"></i> Fournisseur : <strong>${fournisseurNom}</strong>
                                    </p>
                                    <p style="margin: 5px 0; color: #2d5a27;">
                                        <i class="fas fa-list"></i> Nombre de lignes : <strong>${nbLignes}</strong>
                                    </p>
                                    <div style="margin: 10px 0; padding: 10px; background: white; border-radius: 5px; max-height: 150px; overflow-y: auto;">
                                        <p style="margin: 0 0 5px 0; font-weight: 600; color: #2d5a27;">Produits :</p>
                                        ${listeProduits}
                                    </div>
                                    <p style="margin: 5px 0; font-size: 1.1rem; color: #2d5a27; font-weight: 700;">
                                        <i class="fas fa-money-bill-wave"></i> Total : <strong>${total} FCFA</strong>
                                    </p>
                                </div>
                                <p style="color: #155724; font-weight: 500; background: #d4edda; padding: 10px; border-radius: 5px; margin-top: 10px;">
                                    <i class="fas fa-info-circle"></i>
                                    La commande sera envoyée au fournisseur et le stock sera mis à jour à la réception.
                                </p>
                            </div>
                        `,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: '✅ Oui, valider',
                        cancelButtonText: 'Annuler',
                        reverseButtons: true,
                        width: '650px'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Afficher un loader
                            Swal.fire({
                                title: 'Validation en cours...',
                                text: 'Veuillez patienter',
                                allowOutsideClick: false,
                                showConfirmButton: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                            
                            // Créer un input caché pour l'action
                            const form = document.getElementById('formAchat');
                            const inputAction = document.createElement('input');
                            inputAction.type = 'hidden';
                            inputAction.name = 'action';
                            inputAction.value = 'confirmer';
                            form.appendChild(inputAction);
                            
                            // Soumettre le formulaire
                            form.submit();
                        }
                    });
                });
            }
        }

        // Gestionnaire pour le bouton Annuler (lien)
        function initAnnulation() {
            document.querySelectorAll('.btn-annuler').forEach(function(link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = this.getAttribute('href');
                    
                    Swal.fire({
                        title: '❌ Annuler la création ?',
                        text: 'Toutes les données saisies seront perdues.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: '❌ Oui, annuler',
                        cancelButtonText: 'Non, continuer',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = url;
                        }
                    });
                });
            });
        }

        // Messages flash avec SweetAlert
        function initFlashMessages() {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Succès !',
                    text: "{{ session('success') }}",
                    timer: 4000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur !',
                    text: "{{ session('error') }}",
                    timer: 5000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            @endif

            @if(session('warning'))
                Swal.fire({
                    icon: 'warning',
                    title: 'Attention !',
                    text: "{{ session('warning') }}",
                    timer: 4000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            @endif

            @if(session('info'))
                Swal.fire({
                    icon: 'info',
                    title: 'Information',
                    text: "{{ session('info') }}",
                    timer: 4000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            @endif
        }

        // Initialisation
        initCalculs();
        initValidation();
        initAnnulation();
        initFlashMessages();
    });
</script>
@endpush