<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\ProductVariant;
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
        $order->load(['user', 'items', 'items.variant', 'products']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Statuts pour lesquels le stock a déjà été décrémenté (au checkout).
     */
    private const STATUTS_STOCK_SORTI = ['en_attente', 'payee', 'validee', 'en_livraison', 'livree'];

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

        $nouveauStatut = $request->statut;

        try {
            $commande = DB::transaction(function () use ($order, $nouveauStatut) {
                // Verrou : deux clics sur "Annuler" ne peuvent pas restituer le stock deux fois
                $locked = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();

                if ($locked->statut === 'en_livraison') {
                    throw new \RuntimeException('Cette commande est en tournée. Changez son statut depuis la page de la tournée.');
                }
                if ($locked->statut === 'annulee') {
                    throw new \RuntimeException('Une commande annulée ne peut plus être modifiée.');
                }
                if ($locked->statut === 'panier_converti') {
                    throw new \RuntimeException('Cette commande n\'a pas encore été validée par le client.');
                }
                if ($locked->statut === $nouveauStatut) {
                    return null;
                }

                if ($nouveauStatut === 'annulee' && in_array($locked->statut, self::STATUTS_STOCK_SORTI, true)) {
                    $this->restituerStock($locked, "Annulation commande {$locked->num_order}");
                }

                $locked->update(['statut' => $nouveauStatut]);

                return $locked;
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        if ($commande && $commande->statut === 'validee') {
            event(new OrderValidated($commande));
        }

        return redirect()->back()->with('success', 'Statut mis à jour avec succès !');
    }

    /**
     * Supprimer une commande en attente (le stock décrémenté est restitué)
     */
    public function destroy(Order $order){
        try {
            DB::transaction(function () use ($order) {
                $locked = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();

                if (!in_array($locked->statut, ['en_attente', 'payee'], true)) {
                    throw new \RuntimeException('Seules les commandes en attente peuvent être supprimées.');
                }

                $this->restituerStock($locked, "Suppression commande {$locked->num_order}");
                $locked->delete();
            });
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.orders.index')->with('success', 'Commande supprimée avec succès !');
    }

    /**
     * Remet en stock toutes les lignes de la commande (à appeler dans une transaction).
     */
    private function restituerStock(Order $order, string $notes): void
    {
        $order->load('items.variant');

        foreach ($order->items as $item) {
            $variant = $item->variant
                ?? ProductVariant::where('product_id', $item->product_id)->first();

            if (!$variant) {
                throw new \RuntimeException("Variante introuvable pour la ligne #{$item->id} : stock non restitué, opération annulée.");
            }

            $this->stockService->entreeStock(
                variant: $variant,
                quantite: $item->qte,
                type: 'retour_client',
                source: $order,
                notes: $notes,
            );
        }
    }
}