{{-- resources/views/admin/tournees/show.blade.php --}}
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
                        Livraison {{ $tournee->id }}
                    </h1>
                    <p style="color: #6c757d; margin: 5px 0 0 0;">
                        {{ $tournee->zone->nom }} — {{ $tournee->date_tournee->format('d/m/Y') }}
                    </p>
                </div>
                <a href="{{ route('admin.tournees.index') }}" style="
                    background: #e8e0d5; color: #2d5a27; padding: 12px 24px; border-radius: 30px;
                    text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 8px;
                " onmouseover="this.style.background='#d4c9bb'" onmouseout="this.style.background='#e8e0d5'">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>

            <!-- Infos tournée -->
            <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border: 1px solid #e8e0d5; padding: 25px; margin-bottom: 25px;">
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;">
                    <div>
                        <div style="color: #6c757d; font-size: 0.85rem; margin-bottom: 4px;">Livreur</div>
                        <div style="color: #2d5a27; font-weight: 600;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div style="
                                    width: 32px;
                                    height: 32px;
                                    border-radius: 50%;
                                    background: #2d5a27;
                                    color: white;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    font-weight: bold;
                                    font-size: 12px;
                                    flex-shrink: 0;
                                ">
                                    {{ strtoupper(substr($tournee->livreur->prenom ?? '', 0, 1)) }}{{ strtoupper(substr($tournee->livreur->nom ?? '', 0, 1)) }}
                                </div>
                                <span>{{ $tournee->livreur->full_name }}</span>
                            </div>
                        </div>
                        <div style="color: #6c757d; font-size: 0.85rem;">
                            <i class="fas fa-phone" style="color: #b8860b;"></i> {{ $tournee->livreur->tel }}
                        </div>
                    </div>
                    <div>
                        <div style="color: #6c757d; font-size: 0.85rem; margin-bottom: 4px;">Zone</div>
                        <div style="color: #2d5a27; font-weight: 600;">
                            <span style="background: #e8f5e9; color: #2d5a27; padding: 4px 14px; border-radius: 20px; font-size: 0.85rem;">
                                <i class="fas fa-map-marker-alt" style="color: #b8860b;"></i> {{ $tournee->zone->nom }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <div style="color: #6c757d; font-size: 0.85rem; margin-bottom: 4px;">Date</div>
                        <div style="color: #2d5a27; font-weight: 600;">
                            <i class="far fa-calendar-alt" style="color: #b8860b;"></i>
                            {{ $tournee->date_tournee->format('d/m/Y') }}
                        </div>
                    </div>
                    <div>
                        <div style="color: #6c757d; font-size: 0.85rem; margin-bottom: 4px;">Statut</div>
                        @if($tournee->statut === 'en_cours')
                            <span style="background: #fff3cd; color: #856404; padding: 6px 14px; border-radius: 20px; font-size: 0.85rem; font-weight: 500; display: inline-block;">
                                <i class="fas fa-truck-loading"></i> En cours
                            </span>
                        @else
                            <span style="background: #d4edda; color: #155724; padding: 6px 14px; border-radius: 20px; font-size: 0.85rem; font-weight: 500; display: inline-block;">
                                <i class="fas fa-check-circle"></i> Terminée
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Liste des commandes -->
            <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); overflow: hidden; border: 1px solid #e8e0d5; margin-bottom: 25px;">
                <div style="padding: 20px 25px; border-bottom: 1px solid #e8e0d5; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                    <h2 style="font-size: 16px; color: #2d5a27; font-weight: 700; margin: 0;">
                        <i class="fas fa-box" style="color: #b8860b; margin-right: 8px;"></i>
                        Commandes
                    </h2>
                    <span style="background: #f8f5f0; color: #2d5a27; padding: 4px 16px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">
                        {{ $tournee->orders->count() }} commande(s)
                    </span>
                </div>

                <div style="overflow-x: auto;">
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
                                <tr style="border-bottom: 1px solid #f0ebe5; transition: background 0.2s;" onmouseover="this.style.background='#faf8f5'" onmouseout="this.style.background='transparent'">
                                    <td style="padding: 12px 15px; color: #2d5a27; font-weight: 600;">
                                        <span style="background: #e8f5e9; color: #2d5a27; padding: 4px 10px; border-radius: 12px; font-size: 0.8rem;">
                                            {{ $order->num_order }}
                                        </span>
                                    </td>
                                    <td style="padding: 12px 15px;">
                                        <div style="color: #2d5a27; font-weight: 500;">
                                            <i class="fas fa-user" style="color: #b8860b;"></i>
                                            {{ $order->user->prenom ?? $order->user->name ?? 'N/A' }}
                                        </div>
                                        <div style="color: #6c757d; font-size: 0.8rem;">
                                            <i class="fas fa-envelope" style="color: #b8860b;"></i>
                                            {{ $order->user->email ?? '' }}
                                        </div>
                                    </td>
                                    <td style="padding: 12px 15px; color: #2d5a27; font-size: 0.85rem;">
                                        <i class="fas fa-map-pin" style="color: #b8860b;"></i>
                                        {{ Str::limit($order->adresse_precise, 40) }}
                                    </td>
                                    <td style="padding: 12px 15px; text-align: right; font-weight: 700; color: #2d5a27; font-size: 1rem;">
                                        {{ number_format($order->montant_ttc ?? $order->mt_total, 0, ',', ' ') }} FCFA
                                    </td>
                                    <td style="padding: 12px 15px; text-align: center;">
                                        @if($order->statut === 'livree')
                                            <span style="background: #d4edda; color: #155724; padding: 5px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 500; display: inline-block;">
                                                <i class="fas fa-check-circle"></i> Livrée
                                            </span>
                                        @else
                                            <span style="background: #fff3cd; color: #856404; padding: 5px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 500; display: inline-block;">
                                                <i class="fas fa-truck"></i> En livraison
                                            </span>
                                        @endif
                                    </td>
                                    <td style="padding: 12px 15px; text-align: center;">
                                        @if($order->statut !== 'livree' && $tournee->statut === 'en_cours')
                                            <form action="{{ route('admin.tournees.deliver-order', [$tournee, $order]) }}" 
                                                  method="POST" 
                                                  style="display: inline-block;"
                                                  id="deliver-form-{{ $order->id }}">
                                                @csrf
                                                <button type="button" 
                                                        class="btn-livrer"
                                                        data-id="{{ $order->id }}"
                                                        data-num="{{ $order->num_order }}"
                                                        data-client="{{ $order->user->prenom ?? $order->user->name ?? 'N/A' }}"
                                                        style="
                                                            background: #2d5a27; 
                                                            color: white; 
                                                            padding: 7px 14px; 
                                                            border-radius: 20px;
                                                            border: none; 
                                                            font-size: 0.8rem; 
                                                            cursor: pointer;
                                                            transition: all 0.2s;
                                                            display: inline-flex;
                                                            align-items: center;
                                                            gap: 4px;
                                                        " onmouseover="this.style.background='#1e3d1a'" onmouseout="this.style.background='#2d5a27'">
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
                            <i class="fas fa-check-circle" style="color: #28a745;"></i> 
                            <strong>Toutes les commandes ont été livrées.</strong> Vous pouvez clôturer la tournée.
                        @else
                            <i class="fas fa-info-circle" style="color: #856404;"></i> 
                            <strong>{{ $tournee->orders->where('statut', '!=', 'livree')->count() }} commande(s)</strong> en attente de livraison.
                            Toutes les commandes doivent être livrées avant de clôturer la tournée.
                        @endif
                    </div>
                    <form action="{{ route('admin.tournees.close', $tournee) }}" 
                          method="POST" 
                          style="display: inline-block;"
                          id="close-form">
                        @csrf
                        <button type="button" 
                                id="btn-cloturer"
                                class="{{ $tournee->toutesLivrees() ? 'btn-cloturer' : '' }}"
                                data-tournee="{{ $tournee->id }}"
                                data-livreur="{{ $tournee->livreur->full_name ?? 'N/A' }}"
                                data-commandes="{{ $tournee->orders->count() }}"
                                style="
                                    background: {{ $tournee->toutesLivrees() ? '#2d5a27' : '#d4c9bb' }};
                                    color: white; 
                                    padding: 12px 30px; 
                                    border-radius: 30px; 
                                    border: none;
                                    font-weight: 500; 
                                    cursor: {{ $tournee->toutesLivrees() ? 'pointer' : 'not-allowed' }};
                                    transition: all 0.3s ease;
                                    display: inline-flex;
                                    align-items: center;
                                    gap: 8px;
                                " 
                                {{ $tournee->toutesLivrees() ? '' : 'disabled' }}
                                onmouseover="{{ $tournee->toutesLivrees() ? "this.style.background='#1e3d1a'" : '' }}" 
                                onmouseout="{{ $tournee->toutesLivrees() ? "this.style.background='#2d5a27'" : '' }}">
                            <i class="fas fa-flag-checkered"></i> Clôturer la tournée
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestionnaire d'événements pour la livraison d'une commande
    document.querySelectorAll('.btn-livrer').forEach(function(button) {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const num = this.dataset.num;
            const client = this.dataset.client;
            
            Swal.fire({
                title: '✅ Confirmer la livraison ?',
                html: `
                    <div style="text-align: left;">
                        <p style="color: #155724; font-weight: 500;">
                            <i class="fas fa-exclamation-triangle" style="color: #856404;"></i>
                            Vous êtes sur le point de marquer comme livrée la commande :
                        </p>
                        <div style="background: #f8f5f0; padding: 12px; border-radius: 8px; margin: 10px 0;">
                            <p style="font-weight: 600; color: #2d5a27; margin: 0;">
                                <i class="fas fa-box" style="color: #b8860b;"></i> 
                                <strong>${num}</strong>
                            </p>
                            <p style="color: #6c757d; margin: 5px 0 0 0; font-size: 0.9rem;">
                                <i class="fas fa-user" style="color: #b8860b;"></i>
                                Client : <strong>${client}</strong>
                            </p>
                        </div>
                        <p style="color: #155724; font-weight: 500; background: #d4edda; padding: 10px; border-radius: 5px; margin-top: 10px;">
                            <i class="fas fa-info-circle"></i>
                            Cette action confirme que la commande a bien été livrée au client.
                        </p>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '✅ Oui, livrer',
                cancelButtonText: 'Annuler',
                reverseButtons: true,
                width: '550px'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Afficher un loader
                    Swal.fire({
                        title: 'Livraison en cours...',
                        text: 'Veuillez patienter',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Soumettre le formulaire
                    document.getElementById('deliver-form-' + id).submit();
                }
            });
        });
    });

    // Gestionnaire d'événements pour la clôture de la tournée
    const btnCloturer = document.getElementById('btn-cloturer');
    if (btnCloturer) {
        btnCloturer.addEventListener('click', function() {
            const tournee = this.dataset.tournee;
            const livreur = this.dataset.livreur;
            const commandes = this.dataset.commandes;
            
            Swal.fire({
                title: '🏁 Clôturer cette tournée ?',
                html: `
                    <div style="text-align: left;">
                        <p style="color: #155724; font-weight: 500;">
                            <i class="fas fa-exclamation-triangle" style="color: #856404;"></i>
                            Vous êtes sur le point de clôturer la tournée :
                        </p>
                        <div style="background: #f8f5f0; padding: 12px; border-radius: 8px; margin: 10px 0;">
                            <p style="font-weight: 600; color: #2d5a27; margin: 0;">
                                <i class="fas fa-user" style="color: #b8860b;"></i> 
                                <strong>${livreur}</strong>
                            </p>
                            <p style="color: #6c757d; margin: 5px 0 0 0; font-size: 0.9rem;">
                                <i class="fas fa-box" style="color: #b8860b;"></i>
                                Commandes livrées : <strong>${commandes}</strong>
                            </p>
                        </div>
                        <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-top: 10px;">
                            <i class="fas fa-check-circle"></i>
                            Toutes les commandes ont été livrées avec succès !
                        </div>
                        <p style="color: #155724; font-weight: 500; background: #d4edda; padding: 10px; border-radius: 5px; margin-top: 10px;">
                            <i class="fas fa-info-circle"></i>
                            Une fois clôturée, cette tournée ne pourra plus être modifiée.
                        </p>
                    </div>
                `,
                icon: 'success',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '🏁 Oui, clôturer',
                cancelButtonText: 'Annuler',
                reverseButtons: true,
                width: '550px'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Afficher un loader
                    Swal.fire({
                        title: 'Clôture en cours...',
                        text: 'Veuillez patienter',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Soumettre le formulaire
                    document.getElementById('close-form').submit();
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
@endpush