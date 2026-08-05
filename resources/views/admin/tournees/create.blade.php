@extends('layouts.admin')

@section('title', 'Nouvelle tournée - La Réserve Naturelle')

@section('content')
<div class="container" style="padding: 40px 0;">
    <div class="row">
        <div class="col-12">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h1 style="font-family: 'Playfair Display', serif; color: #2d5a27; font-size: 2rem; margin: 0;">
                        <i class="fas fa-plus-circle" style="color: #2d5a27; margin-right: 10px;"></i>
                        Nouvelle tournée
                    </h1>
                    <p style="color: #6c757d; margin: 5px 0 0 0;">Regroupez des commandes validées et assignez un livreur</p>
                </div>
                <a href="{{ route('admin.tournees.index') }}" style="
                    background: #e8e0d5; color: #2d5a27; padding: 12px 24px; border-radius: 30px;
                    text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 8px;
                ">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>

            @if($errors->any())
                <div style="background: #f8d7da; color: #721c24; padding: 15px 20px; border-radius: 8px; border-left: 4px solid #dc3545; margin-bottom: 20px;">
                    <strong>Des erreurs sont survenues :</strong>
                    <ul style="margin: 5px 0 0 20px; padding: 0;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('error'))
                <div style="background: #f8d7da; color: #721c24; padding: 12px 20px; border-radius: 8px; border-left: 4px solid #dc3545; margin-bottom: 20px;">
                    {{ session('error') }}
                </div>
            @endif

            <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border: 1px solid #e8e0d5; padding: 40px;">
                <form action="{{ route('admin.tournees.store') }}" method="POST" id="tournee-form">
                    @csrf

                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 25px; margin-bottom: 30px;">
                        <!-- Zone -->
                        <div>
                            <label for="zone_id" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 8px; font-size: 0.95rem;">
                                <i class="fas fa-map-marker-alt" style="color: #2d5a27; margin-right: 5px;"></i>
                                Zone <span style="color: #dc3545;">*</span>
                            </label>
                            <select name="zone_id" id="zone_id" required style="
                                width: 100%; padding: 12px 16px; border: 2px solid #e8e0d5; border-radius: 8px;
                                font-size: 1rem; background: #faf8f5; outline: none; cursor: pointer;
                            ">
                                <option value="">-- Sélectionner --</option>
                                @foreach($zones as $zone)
                                    <option value="{{ $zone->id }}">{{ $zone->nom }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Livreur -->
                        <div>
                            <label for="livreur_id" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 8px; font-size: 0.95rem;">
                                <i class="fas fa-user" style="color: #2d5a27; margin-right: 5px;"></i>
                                Livreur <span style="color: #dc3545;">*</span>
                            </label>
                            <select name="livreur_id" id="livreur_id" required style="
                                width: 100%; padding: 12px 16px; border: 2px solid #e8e0d5; border-radius: 8px;
                                font-size: 1rem; background: #faf8f5; outline: none; cursor: pointer;
                            ">
                                <option value="">-- Sélectionner --</option>
                                @forelse($livreurs as $livreur)
                                    <option value="{{ $livreur->id }}">{{ $livreur->full_name }} ({{ $livreur->tel }})</option>
                                @empty
                                    <option value="" disabled>Aucun livreur disponible</option>
                                @endforelse
                            </select>
                        </div>

                        <!-- Date -->
                        <div>
                            <label for="date_tournee" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 8px; font-size: 0.95rem;">
                                <i class="fas fa-calendar" style="color: #2d5a27; margin-right: 5px;"></i>
                                Date <span style="color: #dc3545;">*</span>
                            </label>
                            <input type="date" name="date_tournee" id="date_tournee" required
                                   value="{{ old('date_tournee', date('Y-m-d')) }}"
                                   style="width: 100%; padding: 12px 16px; border: 2px solid #e8e0d5; border-radius: 8px; font-size: 1rem; background: #faf8f5; outline: none;">
                        </div>
                    </div>

                    <!-- Liste des commandes (chargée en AJAX) -->
                    <div style="margin-bottom: 25px;">
                        <label for="order_ids" style="display: block; font-weight: 600; color: #2d5a27; margin-bottom: 8px; font-size: 0.95rem;">
                            <i class="fas fa-box" style="color: #2d5a27; margin-right: 5px;"></i>
                            Commandes validées de la zone <span style="color: #dc3545;">*</span>
                        </label>
                        <select name="order_ids[]" id="order_ids" multiple required size="8" style="
                            width: 100%; padding: 10px; border: 2px solid #e8e0d5; border-radius: 8px;
                            font-size: 1rem; background: #faf8f5; outline: none;
                        ">
                            <option disabled>Sélectionnez d'abord une zone</option>
                        </select>
                        <small style="color: #6c757d;">Ctrl/Cmd + clic pour sélectionner plusieurs commandes.</small>
                    </div>

                    <div style="display: flex; justify-content: flex-end; gap: 15px; padding-top: 25px; border-top: 1px solid #e8e0d5;">
                        <a href="{{ route('admin.tournees.index') }}" style="
                            background: #e8e0d5; color: #2d5a27; padding: 12px 30px; border-radius: 30px;
                            text-decoration: none; font-weight: 500;
                        ">
                            <i class="fas fa-times"></i> Annuler
                        </a>
                        <button type="submit" style="
                            background: #2d5a27; color: white; padding: 12px 35px; border-radius: 30px;
                            border: none; font-weight: 500; cursor: pointer; font-size: 1rem;
                        ">
                            <i class="fas fa-save"></i> Créer la tournée
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const zoneSelect = document.getElementById('zone_id');
    const orderSelect = document.getElementById('order_ids');

    zoneSelect.addEventListener('change', function() {
        const zoneId = this.value;

        if (!zoneId) {
            orderSelect.innerHTML = '<option disabled>Sélectionnez d\'abord une zone</option>';
            return;
        }

        orderSelect.innerHTML = '<option disabled>Chargement...</option>';

        fetch(`/admin/tournees/zones/${zoneId}/orders`)
            .then(res => res.json())
            .then(orders => {
                if (orders.length === 0) {
                    orderSelect.innerHTML = '<option disabled>Aucune commande validée dans cette zone</option>';
                    return;
                }

                orderSelect.innerHTML = orders.map(order => {
                    const client = order.user?.name ?? order.user?.prenom ?? 'Client';
                    const montant = Number(order.mt_total).toLocaleString('fr-FR');
                    const suffixe = order.mode_livraison === 'expedition' && order.ville_expedition
                        ? ` — 📦 Expédition vers ${order.ville_expedition}`
                        : ' — 🏠 Domicile';

                    return `<option value="${order.id}">${order.num_order} — ${client} — ${montant} FCFA${suffixe}</option>`;
                }).join('');
            })
            .catch(() => {
                orderSelect.innerHTML = '<option disabled>Erreur de chargement</option>';
            });
    });
});
</script>
@endsection