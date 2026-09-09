{{-- resources/views/admin/inventaires/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'Détail Inventaire - La Réserve Naturelle')

@section('content')
<div class="container" style="padding: 40px 0;">
    <div class="row">
        <div class="col-12">
            <!-- Entête avec titre et bouton retour -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h1 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 2rem; margin: 0;">
                        <i class="fas fa-clipboard-list" style="color: #b8860b; margin-right: 10px;"></i>
                        {{ $inventaire->reference }}
                    </h1>
                    <p style="color: #6c757d; margin: 5px 0 0 0;">
                        <i class="far fa-calendar-alt" style="color: #b8860b; margin-right: 5px;"></i>
                        {{ $inventaire->date_inventaire->format('d/m/Y') }}
                        <span style="margin: 0 8px;">|</span>
                        <i class="fas fa-user" style="color: #b8860b; margin-right: 5px;"></i>
                        par {{ $inventaire->user->nom ?? 'N/A' }}
                    </p>
                </div>
                <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
                    <!-- Badge statut -->
                    @if($inventaire->statut === 'en_cours')
                        <span style="
                            background: #fff3cd;
                            color: #856404;
                            padding: 8px 20px;
                            border-radius: 30px;
                            font-size: 0.9rem;
                            font-weight: 600;
                            display: inline-flex;
                            align-items: center;
                            gap: 8px;
                        ">
                            <i class="fas fa-clock"></i> En cours
                        </span>
                    @elseif($inventaire->statut === 'valide')
                        <span style="
                            background: #d4edda;
                            color: #155724;
                            padding: 8px 20px;
                            border-radius: 30px;
                            font-size: 0.9rem;
                            font-weight: 600;
                            display: inline-flex;
                            align-items: center;
                            gap: 8px;
                        ">
                            <i class="fas fa-check-circle"></i> Validé
                        </span>
                    @elseif($inventaire->statut === 'annule')
                        <span style="
                            background: #f8d7da;
                            color: #721c24;
                            padding: 8px 20px;
                            border-radius: 30px;
                            font-size: 0.9rem;
                            font-weight: 600;
                            display: inline-flex;
                            align-items: center;
                            gap: 8px;
                        ">
                            <i class="fas fa-times-circle"></i> Annulé
                        </span>
                    @endif

                    <a href="{{ route('admin.inventaires.index') }}" style="
                        background: #e8e0d5;
                        color: #2d5a27;
                        padding: 8px 20px;
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
            </div>

            <!-- Notes -->
            @if ($inventaire->notes)
                <div style="
                    background: #f8f5f0;
                    padding: 15px 20px;
                    border-radius: 8px;
                    border-left: 4px solid #b8860b;
                    margin-bottom: 25px;
                ">
                    <div style="display: flex; align-items: flex-start; gap: 10px;">
                        <i class="fas fa-sticky-note" style="color: #b8860b; font-size: 18px; margin-top: 2px;"></i>
                        <div>
                            <strong style="color: #2d5a27;">Notes :</strong>
                            <span style="color: #6c757d;">{{ $inventaire->notes }}</span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Tableau des produits -->
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
                                <th style="padding: 15px 20px; text-align: left; font-weight: 600; color: #2d5a27; width: 22%;">
                                    <i class="fas fa-box" style="color: #b8860b; margin-right: 5px;"></i>
                                    Produit
                                </th>
                                <th style="padding: 15px 20px; text-align: left; font-weight: 600; color: #2d5a27; width: 15%;">
                                    <i class="fas fa-tag" style="color: #b8860b; margin-right: 5px;"></i>
                                    Référence
                                </th>
                                <th style="padding: 15px 20px; text-align: center; font-weight: 600; color: #2d5a27; width: 15%;">
                                    <i class="fas fa-cube" style="color: #b8860b; margin-right: 5px;"></i>
                                    Qté théorique
                                </th>
                                <th style="padding: 15px 20px; text-align: center; font-weight: 600; color: #2d5a27; width: 20%;">
                                    <i class="fas fa-pen" style="color: #b8860b; margin-right: 5px;"></i>
                                    Qté réelle
                                </th>
                                <th style="padding: 15px 20px; text-align: center; font-weight: 600; color: #2d5a27; width: 13%;">
                                    <i class="fas fa-arrows-h" style="color: #b8860b; margin-right: 5px;"></i>
                                    Écart
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <form id="form-valider" method="POST" action="{{ route('admin.inventaires.valider', $inventaire) }}">
                                @csrf
                                @foreach ($inventaire->produits as $ligne)
                                    <tr style="border-bottom: 1px solid #f0ebe5; transition: background 0.2s;" onmouseover="this.style.background='#faf8f5'" onmouseout="this.style.background='transparent'">
                                        <td style="padding: 15px 20px; font-weight: 500; color: #2d5a27;">
                                            <div style="display: flex; align-items: center; gap: 10px;">
                                                <div style="
                                                    width: 36px;
                                                    height: 36px;
                                                    border-radius: 8px;
                                                    background: #e8f5e9;
                                                    display: flex;
                                                    align-items: center;
                                                    justify-content: center;
                                                    flex-shrink: 0;
                                                ">
                                                    <i class="fas fa-box" style="color: #b8860b;"></i>
                                                </div>
                                                {{ $ligne->product->designation ?? 'N/A' }}
                                            </div>
                                        </td>
                                        <td style="padding: 15px 20px;">
                                            <span style="
                                                background: #e8f5e9;
                                                color: #2d5a27;
                                                padding: 4px 12px;
                                                border-radius: 20px;
                                                font-size: 0.8rem;
                                                font-weight: 600;
                                                display: inline-block;
                                            ">
                                                {{ $ligne->product->reference_prod ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td style="padding: 15px 20px; text-align: center; font-weight: 600; color: #2d5a27;">
                                            {{ $ligne->qte_theorique_live ?? $ligne->qte_theorique ?? 0 }}
                                        </td>
                                        <td style="padding: 15px 20px; text-align: center;">
                                            <input type="hidden" name="lignes[{{ $loop->index }}][id]" value="{{ $ligne->id }}">
                                            <input
                                                type="number"
                                                name="lignes[{{ $loop->index }}][qte_reelle]"
                                                value="{{ $ligne->qte_reelle }}"
                                                min="0"
                                                class="qte-reelle"
                                                data-theorique="{{ $ligne->qte_theorique_live ?? $ligne->qte_theorique ?? 0 }}"
                                                {{ $inventaire->statut !== 'en_cours' ? 'disabled' : '' }}
                                                style="
                                                    width: 100px;
                                                    padding: 8px 12px;
                                                    border: 2px solid #e8e0d5;
                                                    border-radius: 8px;
                                                    font-size: 0.95rem;
                                                    text-align: center;
                                                    transition: border-color 0.3s;
                                                    outline: none;
                                                    {{ $inventaire->statut !== 'en_cours' ? 'background: #f8f5f0;' : '' }}
                                                "
                                                onfocus="this.style.borderColor='#2d5a27'"
                                                onblur="this.style.borderColor='#e8e0d5'"
                                            >
                                        </td>
                                        <td class="ecart-cell" style="
                                            padding: 15px 20px;
                                            text-align: center;
                                            font-weight: 700;
                                            font-size: 1.1rem;
                                            color: {{ ($ligne->ecart_live ?? $ligne->ecart ?? 0) == 0 ? '#2d5a27' : (($ligne->ecart_live ?? $ligne->ecart ?? 0) > 0 ? '#28a745' : '#dc3545') }};
                                        ">
                                            {{ ($ligne->ecart_live ?? $ligne->ecart ?? 0) > 0 ? '+' : '' }}{{ $ligne->ecart_live ?? $ligne->ecart ?? 0 }}
                                        </td>
                                    </tr>
                                @endforeach
                            </form>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Actions : boutons Valider et Annuler --}}
            @if ($inventaire->statut === 'en_cours')
                <div style="margin-top: 25px; display: flex; gap: 15px; flex-wrap: wrap;">
                    <button type="button" id="btn-valider" style="
                        background: linear-gradient(135deg, #28a745 0%, #2d5a27 100%);
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
                        box-shadow: 0 4px 15px rgba(40,167,69,0.3);
                    " onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 25px rgba(40,167,69,0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(40,167,69,0.3)'">
                        <i class="fas fa-check-circle"></i> Valider l'inventaire
                    </button>

                    <button type="button" id="btn-annuler" style="
                        background: #dc3545;
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
                        box-shadow: 0 4px 15px rgba(220,53,69,0.25);
                    " onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 25px rgba(220,53,69,0.35)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(220,53,69,0.25)'">
                        <i class="fas fa-times-circle"></i> Annuler l'inventaire
                    </button>

                    <!-- Formulaire d'annulation -->
                    <form id="form-annuler" method="POST" action="{{ route('admin.inventaires.annuler', $inventaire) }}" style="display: none;">
                        @csrf
                    </form>
                </div>
            @endif

            <!-- Statistiques -->
            <div style="
                margin-top: 30px;
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 15px;
            ">
                <div style="
                    background: white;
                    padding: 15px 20px;
                    border-radius: 8px;
                    border: 1px solid #e8e0d5;
                    text-align: center;
                ">
                    <div style="font-size: 0.8rem; color: #6c757d; text-transform: uppercase; letter-spacing: 1px;">
                        <i class="fas fa-boxes" style="color: #b8860b;"></i> Total produits
                    </div>
                    <div style="font-size: 1.8rem; font-weight: 700; color: #2d5a27;">
                        {{ $inventaire->produits->count() }}
                    </div>
                </div>
                <div style="
                    background: white;
                    padding: 15px 20px;
                    border-radius: 8px;
                    border: 1px solid #e8e0d5;
                    text-align: center;
                ">
                    <div style="font-size: 0.8rem; color: #6c757d; text-transform: uppercase; letter-spacing: 1px;">
                        <i class="fas fa-plus-circle" style="color: #28a745;"></i> Écarts positifs
                    </div>
                    <div style="font-size: 1.8rem; font-weight: 700; color: #28a745;">
                        {{ $inventaire->produits->filter(fn($l) => ($l->ecart_live ?? $l->ecart ?? 0) > 0)->count() }}
                    </div>
                </div>
                <div style="
                    background: white;
                    padding: 15px 20px;
                    border-radius: 8px;
                    border: 1px solid #e8e0d5;
                    text-align: center;
                ">
                    <div style="font-size: 0.8rem; color: #6c757d; text-transform: uppercase; letter-spacing: 1px;">
                        <i class="fas fa-minus-circle" style="color: #dc3545;"></i> Écarts négatifs
                    </div>
                    <div style="font-size: 1.8rem; font-weight: 700; color: #dc3545;">
                        {{ $inventaire->produits->filter(fn($l) => ($l->ecart_live ?? $l->ecart ?? 0) < 0)->count() }}
                    </div>
                </div>
                <div style="
                    background: white;
                    padding: 15px 20px;
                    border-radius: 8px;
                    border: 1px solid #e8e0d5;
                    text-align: center;
                ">
                    <div style="font-size: 0.8rem; color: #6c757d; text-transform: uppercase; letter-spacing: 1px;">
                        <i class="fas fa-check-circle" style="color: #2d5a27;"></i> Écarts nuls
                    </div>
                    <div style="font-size: 1.8rem; font-weight: 700; color: #2d5a27;">
                        {{ $inventaire->produits->filter(fn($l) => ($l->ecart_live ?? $l->ecart ?? 0) == 0)->count() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestionnaire pour le calcul dynamique des écarts
    const inputs = document.querySelectorAll('.qte-reelle');

    inputs.forEach(input => {
        input.addEventListener('input', (e) => {
            const theorique = parseInt(e.target.dataset.theorique) || 0;
            const reelle = parseInt(e.target.value) || 0;
            const ecart = reelle - theorique;
            const row = e.target.closest('tr');
            const cell = row ? row.querySelector('.ecart-cell') : null;

            if (cell) {
                cell.textContent = (ecart > 0 ? '+' : '') + ecart;
                cell.style.color = ecart === 0 ? '#2d5a27' : (ecart > 0 ? '#28a745' : '#dc3545');
            }
        });
    });

    // Gestionnaire pour le bouton Valider
    const btnValider = document.getElementById('btn-valider');
    if (btnValider) {
        btnValider.addEventListener('click', function() {
            // Compter les lignes avec des écarts
            const ecartCells = document.querySelectorAll('.ecart-cell');
            let ecartsPositifs = 0;
            let ecartsNegatifs = 0;
            let ecartsNuls = 0;

            ecartCells.forEach(cell => {
                const value = parseInt(cell.textContent) || 0;
                if (value > 0) ecartsPositifs++;
                else if (value < 0) ecartsNegatifs++;
                else ecartsNuls++;
            });

            const totalProduits = ecartCells.length;

            let html = `
                <div style="text-align: left;">
                    <p style="color: #155724; font-weight: 500;">
                        <i class="fas fa-exclamation-triangle" style="color: #856404;"></i>
                        Vous êtes sur le point de valider cet inventaire.
                    </p>
                    <div style="background: #f8f5f0; padding: 12px; border-radius: 8px; margin: 10px 0;">
                        <p style="margin: 5px 0; font-weight: 600; color: #2d5a27;">
                            <i class="fas fa-boxes"></i> Total produits : <strong>${totalProduits}</strong>
                        </p>
                        <p style="margin: 5px 0; color: #28a745;">
                            <i class="fas fa-plus-circle"></i> Écarts positifs : <strong>${ecartsPositifs}</strong>
                        </p>
                        <p style="margin: 5px 0; color: #dc3545;">
                            <i class="fas fa-minus-circle"></i> Écarts négatifs : <strong>${ecartsNegatifs}</strong>
                        </p>
                        <p style="margin: 5px 0; color: #2d5a27;">
                            <i class="fas fa-check-circle"></i> Écarts nuls : <strong>${ecartsNuls}</strong>
                        </p>
                    </div>
                    <p style="color: #155724; font-weight: 500; background: #d4edda; padding: 10px; border-radius: 5px; margin-top: 10px;">
                        <i class="fas fa-info-circle"></i>
                        Le stock sera ajusté selon les écarts constatés.
                    </p>
                    <p style="color: #721c24; font-weight: 500; background: #f8d7da; padding: 10px; border-radius: 5px; margin-top: 10px;">
                        <i class="fas fa-exclamation-circle"></i>
                        Cette action est irréversible !
                    </p>
                </div>
            `;

            Swal.fire({
                title: '✅ Valider cet inventaire ?',
                html: html,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '✅ Oui, valider',
                cancelButtonText: 'Annuler',
                reverseButtons: true,
                width: '600px'
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
                    
                    // Soumettre le formulaire
                    document.getElementById('form-valider').submit();
                }
            });
        });
    }

    // Gestionnaire pour le bouton Annuler
    const btnAnnuler = document.getElementById('btn-annuler');
    if (btnAnnuler) {
        btnAnnuler.addEventListener('click', function() {
            Swal.fire({
                title: '❌ Annuler cet inventaire ?',
                html: `
                    <div style="text-align: left;">
                        <p style="color: #721c24; font-weight: 500;">
                            <i class="fas fa-exclamation-triangle" style="color: #856404;"></i>
                            Toutes les données saisies seront perdues.
                        </p>
                        <p style="color: #721c24; font-weight: 500; background: #f8d7da; padding: 10px; border-radius: 5px; margin-top: 10px;">
                            <i class="fas fa-exclamation-circle"></i>
                            Cette action est irréversible !
                        </p>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '❌ Oui, annuler',
                cancelButtonText: 'Non, garder',
                reverseButtons: true,
                width: '500px'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Afficher un loader
                    Swal.fire({
                        title: 'Annulation en cours...',
                        text: 'Veuillez patienter',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Soumettre le formulaire d'annulation
                    document.getElementById('form-annuler').submit();
                }
            });
        });
    }

    // Messages flash avec SweetAlert
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
});
</script>
@endsection

@push('styles')
<style>
    /* Animation */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .container > div > div:first-child {
        animation: fadeIn 0.4s ease;
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
            flex-direction: column !important;
        }
        .container > div > div:first-child > div:last-child a {
            width: 100%;
            justify-content: center;
        }
        table {
            font-size: 0.85rem !important;
        }
        th, td {
            padding: 10px 12px !important;
        }
        input[type="number"] {
            width: 70px !important;
        }
        .container > div > div:last-child {
            grid-template-columns: 1fr 1fr !important;
        }
    }
</style>
@endpush