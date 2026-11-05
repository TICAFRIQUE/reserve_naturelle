<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use App\Services\StockService;
use App\Events\OrderValidated;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Services\RecueCommandeService;
use App\Notifications\OrderValidatedNotification;

class OrderController extends Controller
{
    public function __construct(protected StockService $stockService) {}

    /**
     * Statuts pour lesquels le stock a déjà été décrémenté (au checkout).
     */
    private const STATUTS_STOCK_SORTI = ['en_attente', 'payee', 'validee', 'en_livraison', 'livree'];

    /**
     * Afficher la liste des commandes
     */
    public function index(Request $request)
    {
        $orders = Order::with(['user', 'zone'])
            ->withCount('items')
            ->where('statut', '!=', 'panier_converti')
            ->when($request->filled('statut'), fn($q) => $q->where('statut', $request->statut))
            ->when($request->filled('date_debut'), fn($q) => $q->whereDate('date_order', '>=', $request->date_debut))
            ->when($request->filled('date_fin'), fn($q) => $q->whereDate('date_order', '<=', $request->date_fin))
            ->latest('date_order')
            ->paginate(10)
            ->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Afficher une commande spécifique
     */
    public function show(Order $order)
    {
        $order->load(['user', 'items', 'items.variant', 'products']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Changer le statut d'une commande
     */
    public function changeStatus(Request $request, Order $order)
    {
        $validator = Validator::make($request->all(), [
            'statut' => 'required|in:validee,livree,annulee',
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
                    throw new \RuntimeException('Cette commande est en tournée (ancien système).');
                }
                if (in_array($locked->statut, ['annulee', 'livree'], true)) {
                    throw new \RuntimeException('Une commande ' . ($locked->statut === 'livree' ? 'livrée' : 'annulée') . ' ne peut plus être modifiée.');
                }
                if ($locked->statut === 'panier_converti') {
                    throw new \RuntimeException('Cette commande n\'a pas encore été validée par le client.');
                }
                if ($locked->statut === $nouveauStatut) {
                    return null;
                }

                $transitions = [
                    'validee'    => ['livree', 'annulee'],
                    // anciennes commandes
                    'en_attente' => ['validee', 'annulee'],
                    'payee'      => ['validee', 'annulee'],
                ];
                if (!in_array($nouveauStatut, $transitions[$locked->statut] ?? [], true)) {
                    throw new \RuntimeException("Passage de « {$locked->statut} » à « {$nouveauStatut} » non autorisé.");
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

        // Uniquement pour les anciennes commandes en_attente/payee passées en validee
        if ($commande && $commande->statut === 'validee') {
            event(new OrderValidated($commande));
            try {
                $commande->user?->notify(new OrderValidatedNotification($commande));
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return redirect()->back()->with('success', 'Statut mis à jour avec succès !');
    }

    /**
     * Supprimer une ancienne commande en attente (le stock décrémenté est restitué)
     */
    public function destroy(Order $order)
    {
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

    /**
     * Télécharger le ticket PDF d'une commande (régénéré à chaque appel).
     */
    public function downloadTicket(Order $order, RecueCommandeService $service)
    {
        $path = $service->generate($order);
        return Storage::disk('local')->download($path, "recu-{$order->num_order}.pdf");
    }
}