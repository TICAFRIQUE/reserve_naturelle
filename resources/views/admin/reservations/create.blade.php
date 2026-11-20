@extends('layouts.admin')

@section('title', 'Nouvelle réservation - La Réserve Naturelle')

@section('content')
<div class="container" style="padding: 40px 0; width: 700px; margin-left:300px;">
    <h1 style="font-family:'Playfair Display',serif; color:#2d5a27;">
        <i class="fas fa-plus-circle"></i> Nouvelle réservation
    </h1>

    @if(session('error'))
        <div style="background:#f8d7da; color:#721c24; padding:12px 20px; border-radius:8px; margin:15px 0;">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div style="background:#f8d7da; color:#721c24; padding:12px 20px; border-radius:8px; margin:15px 0;">
            <ul style="margin:0; padding-left:18px;">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $input = 'width:100%; padding:10px 15px; border:1px solid #e8e0d5; border-radius:8px; background:#fff; font-size:0.95rem;';
        $label = 'display:block; font-weight:600; color:#2d5a27; font-size:0.9rem; margin-bottom:5px;';
    @endphp

    <form method="POST" action="{{ route('admin.reservations.store') }}"
          style="background:#f8f5f0; border:1px solid #e8e0d5; border-radius:12px; padding:24px; margin-top:20px; display:grid; gap:16px;">
        @csrf

        {{-- Client --}}
        <div>
            <label for="user_id" style="{{ $label }}">Client <span style="color:#dc3545;">*</span></label>
            <select name="user_id" id="user_id" required style="{{ $input }}">
                <option value="">-- Sélectionnez --</option>
                @foreach($clients as $c)
                    <option value="{{ $c->id }}" {{ old('user_id') == $c->id ? 'selected' : '' }}>
                        {{ trim(($c->prenom ?? '') . ' ' . ($c->nom ?? $c->name ?? '')) }} — {{ $c->tel }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Produit --}}
        <div>
            <label for="product_variant_id" style="{{ $label }}">Produit <span style="color:#dc3545;">*</span></label>
            <select name="product_variant_id" id="product_variant_id" required style="{{ $input }}">
                <option value="">-- Sélectionnez --</option>
                @foreach($variants as $v)
                    <option value="{{ $v->id }}" {{ old('product_variant_id') == $v->id ? 'selected' : '' }}>
                        {{ $v->libelle }} — {{ number_format($v->prix_vente, 0, ',', ' ') }} FCFA
                        (stock : {{ $v->qte_dispo }})
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Quantité / Statut / Date --}}
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(160px, 1fr)); gap:16px;">
            <div>
                <label for="qte" style="{{ $label }}">Quantité <span style="color:#dc3545;">*</span></label>
                <input type="number" name="qte" id="qte" min="1" max="1000" value="{{ old('qte', 1) }}" required style="{{ $input }}">
            </div>
            <div>
                <label for="statut" style="{{ $label }}">Statut initial <span style="color:#dc3545;">*</span></label>
                <select name="statut" id="statut" required style="{{ $input }}">
                    <option value="validee" {{ old('statut', 'validee') === 'validee' ? 'selected' : '' }}>Validée</option>
                    <option value="payee" {{ old('statut') === 'payee' ? 'selected' : '' }}>Payée</option>
                </select>
            </div>
            <div>
                <label for="date_reservation" style="{{ $label }}">Date <span style="color:#dc3545;">*</span></label>
                <input type="date" name="date_reservation" id="date_reservation"
                       value="{{ old('date_reservation', now()->toDateString()) }}" required style="{{ $input }}">
            </div>
        </div>

        {{-- Note --}}
        {{-- <div>
            <label for="note" style="{{ $label }}">Note (facultatif)</label>
            <textarea name="note" id="note" rows="2" maxlength="500" style="{{ $input }}">{{ old('note') }}</textarea>
        </div> --}}

        {{-- Actions --}}
        <div style="display:flex; gap:30px; flex-wrap:wrap; margin-left:200px;">
            <button type="submit" style="background:#2d5a27; color:#fff; border:none; padding:10px 28px; border-radius:30px; cursor:pointer; font-weight:500;">
                <i class="fas fa-save"></i> Enregistrer
            </button>
            <a href="{{ route('admin.reservations.index') }}"
               style="background:#e8e0d5; color:#2d5a27; padding:10px 28px; border-radius:30px; text-decoration:none; font-weight:500;">
                Annuler
            </a>
        </div>
    </form>
</div>
@endsection