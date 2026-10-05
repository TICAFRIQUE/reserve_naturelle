<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request){
        $query = User::query();

        // Filtre par rôle
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Recherche par nom/email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                    ->orWhere('prenom', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $users = $query->orderBy('nom')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(){
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request){
        // Validation
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'string', Password::min(8)->letters()->numbers(), 'confirmed'],
            'tel' => ['required', 'regex:/^[0-9]{8,15}$/'],
            'role' => ['required', Rule::in(['admin', 'fournisseur', 'user'])],
        ]);

        // Hash du mot de passe
        $data['password'] = Hash::make($data['password']);
        User::create($data);
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id){
        // Afficher un utilisateur avec ses relations
        $user = User::with(['carts', 'achats', 'orders'])->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id){
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id){
        $user = User::findOrFail($id);

        // Validation
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', Password::min(8)->letters()->numbers(), 'confirmed'],
            'tel' => ['required', 'regex:/^[0-9]{8,15}$/'],
            'role' => ['required', Rule::in(['admin', 'fournisseur', 'user'])],
        ]);

        // Empêcher un administrateur de se rétrograder lui-même
        if ($user->id === auth()->id() && $data['role'] !== 'admin') {
            return redirect()->route('admin.users.index')
                ->with('error', 'Vous ne pouvez pas retirer votre propre rôle administrateur.');
        }

        // Empêcher le retrait du dernier administrateur
        if ($user->role === 'admin' && $data['role'] !== 'admin') {
            $adminCount = User::where('role', 'admin')->count();

            if ($adminCount <= 1) {
                return redirect()->route('admin.users.index')->with('error', 'Impossible de retirer le rôle du dernier administrateur.');
            }
        }

        // Si le mot de passe est rempli, on le hash
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            // Sinon on le retire des données à mettre à jour
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id){
        // Récupérer l'utilisateur
        $user = User::findOrFail($id);

        // Empêcher l'auto-suppression
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        // Empêcher la suppression du dernier administrateur
        if ($user->role === 'admin') {
            $adminCount = User::where('role', 'admin')->count();

            if ($adminCount <= 1) {
                return redirect()->route('admin.users.index')->with('error', 'Impossible de supprimer le dernier administrateur.');
            }
        }

        // Vérifier si l'utilisateur a des commandes ou achats
        if ($user->orders()->exists() || $user->achats()->exists()) {
            return redirect()->route('admin.users.index')->with('error', 'Impossible de supprimer cet utilisateur car il a des commandes ou achats.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé avec succès.');
    }
}