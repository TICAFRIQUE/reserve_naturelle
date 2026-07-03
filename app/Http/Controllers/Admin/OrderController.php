<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;    
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Afficher la liste des commandes
     */
    public function index(){
        $orders = Order::with('user')->paginate(10);
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
            'statut' => 'required|in:en_attente,validee,livree,annulee'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $order->update(['statut' => $request->statut]);

        return redirect()->back()
            ->with('success', 'Statut mis à jour avec succès !');
    }
}