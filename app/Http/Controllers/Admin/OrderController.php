<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\StockService;
use App\Events\OrderValidated;
use Illuminate\Support\Facades;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function __construct(protected StockService $stockService) {}
    /**
     * Afficher la liste des commandes
     */
    public function index(){
        $orders = Order::with(['user', 'zone'])->where('statut', '!=', 'panier_converti')
                ->latest('date_order')->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Afficher une commande spécifique
     */
    public function show(Order $order){
        $order->load(['user', 'items', 'products']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Changer le statut d'une commande
     **/
    public function changeStatus(Request $request, Order $order){
        $validator = Validator::make($request->all(), [
            // 'livree' retiré : ne peut être défini que via TourneeController::markOrderDelivered
            'statut' => 'required|in:en_attente,payee,validee,annulee'
        ]);
        if ($validator->fails()) {
            return back()->with('error', $validator->errors()->first());
        }

        if ($order->statut === 'en_livraison') {
            return back()->with('error', 'Cette commande est en tournée. Changez son statut depuis la page de la tournée.');
        }
        $stockDejaDecremente = in_array($order->statut, ['payee', 'validee', 'livree']);
        $nouveauStatut = $request->statut;

        DB::transaction(function () use ($order, $nouveauStatut, $stockDejaDecremente) {
            $order->update(['statut' => $nouveauStatut]);

            if ($nouveauStatut === 'annulee' && $stockDejaDecremente && $order->wasChanged('statut')) {
                $order->load('items.product');
                foreach ($order->items as $item) {
                    $this->stockService->entreeStock(
                        product: $item->product,
                        quantite: $item->qte,
                        type: 'retour_client',
                        source: $order,
                        notes: "Annulation commande {$order->num_order}",
                    );
                }
            }
        });

        if ($order->statut === 'validee' && $order->wasChanged('statut')) {
            event(new OrderValidated($order));
        }

        return redirect()->back()->with('success', 'Statut mis à jour avec succès !');
    }
        /**
     * Supprimer une commande en attente
     */
    public function destroy(Order $order){
        if (!in_array($order->statut, ['en_attente', 'payee'])) {
             return redirect()->back()->with('error', 'Seules les commandes en attente peuvent être supprimées.');
        }
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Commande supprimée avec succès !');
    }

}