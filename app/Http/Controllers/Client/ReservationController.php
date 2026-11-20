<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\RecuReservationService;

class ReservationController extends Controller
{
    public function index(){
        $reservations = Reservation::with('variant.product')
            ->where('user_id', auth()->id())
            ->latest('date_reservation')->latest('id')
            ->paginate(10);
        return view('client.reservations.index', compact('reservations'));
    }

    public function store(Request $request){
        $validated = $request->validate([
            'product_variant_id' => ['required', 'exists:product_variants,id'],
            'qte'                => ['required', 'integer', 'min:1', 'max:1000'],
            'note'               => ['nullable', 'string', 'max:500'],
        ]);

        $variant = ProductVariant::where('actif', true)->findOrFail($validated['product_variant_id']);
        if ($variant->qte_dispo > 0) {
            return back()->with('error', "« {$variant->libelle} » est disponible : ajoutez-le directement au panier.");
        }

        Reservation::create([
            'num_reservation'    => 'RES-' . strtoupper(Str::random(8)),
            'user_id'            => auth()->id(),
            'product_id'         => $variant->product_id,
            'product_variant_id' => $variant->id,
            'qte'                => $validated['qte'],
            'prix_unitaire'      => $variant->prix_vente,
            'montant'            => $variant->prix_vente * $validated['qte'],
            'statut'             => 'validee',
            'note'               => $validated['note'] ?? null,
            'date_reservation'   => now(),
        ]);
        return redirect()->route('client.reservations.index')
            ->with('success', 'Réservation enregistrée. Nous vous contacterons dès que le produit sera disponible.');
    }

    public function show(Reservation $reservation){
        abort_unless($reservation->user_id === auth()->id(), 403);
        $reservation->load('variant.product');
        return view('client.reservations.show', compact('reservation'));
    }

    public function cancel(Reservation $reservation){
        abort_unless($reservation->user_id === auth()->id(), 403);

        if ($reservation->statut !== 'validee') {
            return back()->with('error', 'Cette réservation ne peut plus être annulée.');
        }
        $reservation->update(['statut' => 'annulee']);
        return back()->with('success', 'Réservation annulée.');
    }

    public function downloadReceipt(Reservation $reservation, RecuReservationService $service){
        abort_unless($reservation->user_id === auth()->id(), 403);
        abort_if($reservation->statut === 'annulee', 404);
        return $service->make($reservation)->download("recu-{$reservation->num_reservation}.pdf");
    }
}