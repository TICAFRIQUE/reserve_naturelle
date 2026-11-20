<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use App\Models\Reservation;
use App\Services\StockService;
use App\Services\RecuReservationService;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    public function __construct(private StockService $stockService) {}

    public function index(Request $request){
        $reservations = Reservation::with(['user', 'variant.product'])
            ->when($request->filled('statut'), fn($q) => $q->where('statut', $request->statut))
            ->when($request->filled('date_debut'), fn($q) => $q->whereDate('date_reservation', '>=', $request->date_debut))
            ->when($request->filled('date_fin'), fn($q) => $q->whereDate('date_reservation', '<=', $request->date_fin))
            ->latest('date_reservation')->latest('id')
            ->paginate(10)
            ->withQueryString();
        return view('admin.reservations.index', compact('reservations'));
    }

    public function create(){
        $clients  = User::orderBy('email')->get();
        $variants = ProductVariant::with('product')->where('actif', true)->get()
            ->sortBy(fn($v) => $v->libelle)->values();
        return view('admin.reservations.create', compact('clients', 'variants'));
    }

    public function store(Request $request){
        $validated = $request->validate([
            'user_id'            => ['required', 'exists:users,id'],
            'product_variant_id' => ['required', 'exists:product_variants,id'],
            'qte'                => ['required', 'integer', 'min:1', 'max:1000'],
            'statut'             => ['required', 'in:validee,payee'],
            'date_reservation'   => ['required', 'date'],
            'note'               => ['nullable', 'string', 'max:500'],
        ]);

        $variant = ProductVariant::where('actif', true)->findOrFail($validated['product_variant_id']);
        Reservation::create([
            'num_reservation'    => 'RES-' . strtoupper(Str::random(8)),
            'user_id'            => $validated['user_id'],
            'product_id'         => $variant->product_id,
            'product_variant_id' => $variant->id,
            'qte'                => $validated['qte'],
            'prix_unitaire'      => $variant->prix_vente,   // prix figé
            'montant'            => $variant->prix_vente * $validated['qte'],
            'statut'             => $validated['statut'],
            'note'               => $validated['note'] ?? null,
            'date_reservation'   => $validated['date_reservation'],
        ]);
        return redirect()->route('admin.reservations.index')->with('success', 'Réservation créée avec succès.');
    }

    public function show(Reservation $reservation){
        $reservation->load(['user', 'variant.product']);
        return view('admin.reservations.show', compact('reservation'));
    }

    public function changeStatus(Request $request, Reservation $reservation){
        $request->validate(['statut' => 'required|in:payee,livree,annulee']);
        $nouveau = $request->statut;

        $transitions = [
            'validee' => ['payee', 'livree', 'annulee'],
            'payee'   => ['livree', 'annulee'],
        ];

        try {
            DB::transaction(function () use ($reservation, $nouveau, $transitions) {
                $locked = Reservation::whereKey($reservation->id)->lockForUpdate()->firstOrFail();

                if (!in_array($nouveau, $transitions[$locked->statut] ?? [], true)) {
                    throw new \RuntimeException("Passage de « {$locked->statut} » à « {$nouveau} » non autorisé.");
                }

                // La sortie de stock n'a lieu qu'à la remise au client
                if ($nouveau === 'livree') {
                    $variant = ProductVariant::findOrFail($locked->product_variant_id);
                    $this->stockService->sortieStock(
                        variant: $variant,
                        quantite: $locked->qte,
                        type: 'sortie_commande',
                        source: $locked,
                        notes: "Réservation {$locked->num_reservation}",
                    );
                }

                $locked->update(['statut' => $nouveau]);
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }
        return back()->with('success', 'Statut de la réservation mis à jour.');
    }
    
    public function downloadReceipt(Reservation $reservation, RecuReservationService $service){
        abort_if($reservation->statut === 'annulee', 404);
        return $service->make($reservation)->download("recu-{$reservation->num_reservation}.pdf");
    }
}