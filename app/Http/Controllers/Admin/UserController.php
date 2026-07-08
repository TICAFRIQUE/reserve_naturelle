<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

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
            $query->where(function($q) use ($search) {
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
            'password' => 'required|string|min:8|confirmed',
            'tel' => 'nullable|string|max:20',
            'role' => ['required', Rule::in(['admin', 'fournisseur', 'user'])],
        ]);

        // Hash du mot de passe
        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur créé avec succès.');
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
        // Récupérer l'utilisateur à modifier
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id){

        // Récupérer l'utilisateur
        $user = User::findOrFail($id);

        // Validation
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'tel' => 'nullable|string|max:20',
            'role' => ['required', Rule::in(['admin', 'fournisseur', 'user'])],
        ]);

        // Si le mot de passe est rempli, on le hash
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            // Sinon on le retire des données à mettre à jour
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id){
        // Récupérer l'utilisateur
        $user = User::findOrFail($id);

        // Vérifier si l'utilisateur a des commandes ou achats
        if ($user->orders()->exists() || $user->achats()->exists()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Impossible de supprimer cet utilisateur car il a des commandes ou achats.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }

    /**
     * Changer le rôle d'un utilisateur
     */
    // public function changeRole(Request $request, string $id){
    //     $user = User::findOrFail($id);

    //     $request->validate([
    //         'role' => ['required', Rule::in(['admin', 'fournisseur', 'user'])]
    //     ]);

    //     $user->update(['role' => $request->role]);

    //     return redirect()->route('admin.users.index')
    //         ->with('success', 'Rôle de l\'utilisateur mis à jour.');
    // }

    /**
     * Récupérer les utilisateurs par rôle (API)
     */
    // public function getByRole(string $role)
    // {
    //     if (!in_array($role, ['admin', 'fournisseur', 'user'])) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Rôle invalide'
    //         ], 400);
    //     }

    //     $users = User::where('role', $role)->get();

    //     return response()->json([
    //         'status' => 'success',
    //         'data' => $users
    //     ], 200);
    // }
}