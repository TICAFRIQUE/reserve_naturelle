<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;    
use Illuminate\Support\Facades\Validator;
use App\Events\OrderValidated;

class OrderController extends Controller
{
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
     */
    public function changeStatus(Request $request, Order $order){
        $validator = Validator::make($request->all(), [
            'statut' => 'required|in:en_attente,payee,validee,livree,annulee'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $order->update(['statut' => $request->statut]);
        
        //Pour la gestion de reçue
        if ($order->statut === 'validee' && $order->wasChanged('statut')) {
            event(new OrderValidated($order));
        }

        return redirect()->back()->with('success', 'Statut mis à jour avec succès !');
    }
        /**
     * Supprimer une commande en attente
     */
    public function destroy(Order $order){
        if ($order->statut !== 'en_attente') {
            return redirect()->back()->with('error', 'Seules les commandes en attente peuvent être supprimées.');
        }
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Commande supprimée avec succès !');
    }

}