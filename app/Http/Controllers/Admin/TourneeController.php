<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tournee;
use App\Models\Livreur;
use App\Models\Zone;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TourneeController extends Controller
{
        public function index(Request $request){
        $tournees = Tournee::with(['livreur', 'zone', 'orders'])
            ->when($request->filled('search'), fn($q) =>
                $q->whereHas('livreur', fn($q) =>
                    $q->where('nom', 'like', '%' . $request->search . '%')
                    ->orWhere('prenom', 'like', '%' . $request->search . '%')
                )->orWhereHas('zone', fn($q) =>
                    $q->where('nom', 'like', '%' . $request->search . '%')
                )
            )
            ->when($request->filled('statut'), fn($q) =>
                $q->where('statut', $request->statut)
            )
            ->latest('date_tournee')
            ->paginate(10)
            ->withQueryString();

        return view('admin.tournees.index', compact('tournees'));
    }

    /**
     * Formulaire de création : sélection zone -> affiche commandes validées de cette zone
     */
    public function create(){
        $zones = Zone::all();
        $livreurs = Livreur::where('statut', 'disponible')->get();
        return view('admin.tournees.create', compact('zones', 'livreurs'));
    }

    /**
     * Retourne les commandes validées d'une zone (AJAX, utilisé par le formulaire de création)
     */
    public function ordersByZone(Zone $zone){
        $orders = Order::where('zone_id', $zone->id)->where('statut', 'validee')->with('user')
            ->get(['id', 'num_order', 'mt_total', 'user_id', 'mode_livraison', 'ville_expedition']);
        return response()->json($orders);
    }

    public function store(Request $request){
        $validated = $request->validate([
            'livreur_id' => 'required|exists:livreurs,id',
            'zone_id' => 'required|exists:zones,id',
            'date_tournee' => 'required|date',
            'order_ids' => 'required|array|min:1',
            'order_ids.*' => 'exists:orders,id',
        ]);

        $livreur = Livreur::findOrFail($validated['livreur_id']);

        if ($livreur->statut !== 'disponible') {
            return back()->with('error', 'Ce livreur n\'est pas disponible.');
        }

        // Vérifier que toutes les commandes sont bien 'validee' et de la bonne zone
        $orders = Order::whereIn('id', $validated['order_ids'])
            ->where('zone_id', $validated['zone_id'])
            ->where('statut', 'validee')->get();

        if ($orders->count() !== count($validated['order_ids'])) {
            return back()->with('error', 'Certaines commandes sélectionnées ne sont plus valides.');
        }

        DB::transaction(function () use ($validated, $orders, $livreur) {
            $tournee = Tournee::create([
                'livreur_id' => $validated['livreur_id'],
                'zone_id' => $validated['zone_id'],
                'date_tournee' => $validated['date_tournee'],
                'statut' => 'en_cours',
            ]);

            $tournee->orders()->attach($orders->pluck('id'));
            Order::whereIn('id', $orders->pluck('id'))->update(['statut' => 'en_livraison']);
            $livreur->update(['statut' => 'indisponible']);
        });
        return redirect()->route('admin.tournees.index')->with('success', 'Tournée créée avec succès.');
    }

    public function show(Tournee $tournee){
        $tournee->load(['livreur', 'zone', 'orders.user', 'orders.items.product']);
        return view('admin.tournees.show', compact('tournee'));
    }

    /**
     * Marquer une commande de la tournée comme livrée
     */
    public function markOrderDelivered(Tournee $tournee, Order $order){
        abort_unless($tournee->orders->contains($order->id), 404);
        abort_if($order->statut !== 'en_livraison', 403, 'Commande déjà traitée.');

        $order->update(['statut' => 'livree']);
        return back()->with('success', "Commande {$order->num_order} marquée comme livrée.");
    }

    /**
     * Clôturer la tournée (uniquement si toutes les commandes sont livrées)
     */
    public function close(Tournee $tournee){
        abort_if($tournee->statut !== 'en_cours', 403, 'Tournée déjà clôturée.');

        if (!$tournee->toutesLivrees()) {
            return back()->with('error', 'Toutes les commandes doivent être livrées avant de clôturer la tournée.');
        }

        DB::transaction(function () use ($tournee) {
            $tournee->update(['statut' => 'terminee']);
            $tournee->livreur->update(['statut' => 'disponible']);
        });
        return redirect()->route('admin.tournees.index')->with('success', 'Tournée clôturée avec succès.');
    }
   /**
 * Supprimer une tournée non commencée (statut 'en_cours' sans livraisons effectuées, ou 'terminee' obsolète)
 */
    public function destroy(Tournee $tournee){
        if ($tournee->statut === 'en_cours' && $tournee->orders()->where('statut', 'livree')->exists()) {
            return back()->with('error', 'Impossible de supprimer : des commandes de cette tournée ont déjà été livrées.');
        }

        DB::transaction(function () use ($tournee) {
            // Remettre les commandes non livrées en statut "validee" et détacher
            $tournee->orders()->where('statut', '!=', 'livree')->update(['statut' => 'validee']);
            $tournee->orders()->detach();

            // Rendre le livreur disponible si la tournée était en cours
            if ($tournee->statut === 'en_cours') {
                $tournee->livreur->update(['statut' => 'disponible']);
            }
            $tournee->delete();
        });
        return redirect()->route('admin.tournees.index')->with('success', 'Tournée supprimée avec succès.');
    }
}