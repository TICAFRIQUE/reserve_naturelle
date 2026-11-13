<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request){
        $query = User::query();
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                  ->orWhere('prenom', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('tel', 'LIKE', "%{$search}%");
            });
        }
        $users = $query->orderBy('nom')->paginate(10)->withQueryString();
        return view('admin.users.index', compact('users'));
    }

    public function create(){
        return view('admin.users.create');
    }

    public function store(Request $request){
        $this->normalizeInput($request);

        $data = $request->validate([
            'nom'      => 'required|string|max:255',
            'prenom'   => 'required|string|max:255',
            'email'    => 'nullable|string|email|max:255|unique:users,email',
            'password' => ['required', 'string', Password::min(8)->letters()->numbers(), 'confirmed'],
            'tel'      => ['required', 'regex:/^[0-9]{8,15}$/', 'unique:users,tel'],
            'role'     => ['required', Rule::in(['admin', 'fournisseur', 'user'])],
        ]);

        $data['password'] = Hash::make($data['password']);
        User::create($data);
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur créé avec succès.');
    }

    public function show(string $id){
        $user = User::with(['carts', 'achats', 'orders'])->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    public function edit(string $id){
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, string $id){
        $user = User::findOrFail($id);
        $this->normalizeInput($request);

        $data = $request->validate([
            'nom'      => 'required|string|max:255',
            'prenom'   => 'required|string|max:255',
            'email'    => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', Password::min(8)->letters()->numbers(), 'confirmed'],
            'tel'      => ['required', 'regex:/^[0-9]{8,15}$/', Rule::unique('users', 'tel')->ignore($user->id)],
            'role'     => ['required', Rule::in(['admin', 'fournisseur', 'user'])],
        ]);

        if ($user->id === auth()->id() && $data['role'] !== 'admin') {
            return redirect()->route('admin.users.index')
                ->with('error', 'Vous ne pouvez pas retirer votre propre rôle administrateur.');
        }

        if ($user->role === 'admin' && $data['role'] !== 'admin'
            && User::where('role', 'admin')->count() <= 1) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Impossible de retirer le rôle du dernier administrateur.');
        }

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $user->update($data);
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function destroy(string $id){
        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }
        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Impossible de supprimer le dernier administrateur.');
        }
        if ($user->orders()->exists() || $user->achats()->exists()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Impossible de supprimer cet utilisateur car il a des commandes ou achats.');
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé avec succès.');
    }

    /** '' → null pour l'email, chiffres uniquement pour le téléphone */
    private function normalizeInput(Request $request): void{
        $request->merge([
            'email' => $request->filled('email') ? trim($request->email) : null,
            'tel'   => preg_replace('/\D/', '', (string) $request->tel),
        ]);
    }
}