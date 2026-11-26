<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    private function roles()
    {
        return Role::orderBy('name')->pluck('name');
    }

    public function index(Request $request)
    {
        $query = User::with('roles');

        if ($request->filled('role')) {
            $query->role($request->role); // scope Spatie
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
        $roles = $this->roles();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        return view('admin.users.create', ['roles' => $this->roles()]);
    }

    public function store(Request $request)
    {
        $this->normalizeInput($request);

        $data = $request->validate([
            'nom'      => 'required|string|max:255',
            'prenom'   => 'required|string|max:255',
            'email'    => 'nullable|string|email|max:255|unique:users,email',
            'password' => ['required', 'string', Password::min(8)->letters()->numbers(), 'confirmed'],
            'tel'      => ['required', 'regex:/^[0-9]{8,15}$/', 'unique:users,tel'],
            'role'     => ['required', Rule::in($this->roles()->all())],
        ]);

        $this->authorizeRoleAssignment($data['role']);

        $role = $data['role'];
        unset($data['role']);
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);
        $user->syncRoles($role);

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur créé avec succès.');
    }

    public function show(string $id)
    {
        $user = User::with(['roles', 'carts', 'achats', 'orders'])->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    public function edit(string $id)
    {
        $user = User::with('roles')->findOrFail($id);
        return view('admin.users.edit', ['user' => $user, 'roles' => $this->roles()]);
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $this->normalizeInput($request);

        $data = $request->validate([
            'nom'      => 'required|string|max:255',
            'prenom'   => 'required|string|max:255',
            'email'    => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', Password::min(8)->letters()->numbers(), 'confirmed'],
            'tel'      => ['required', 'regex:/^[0-9]{8,15}$/', Rule::unique('users', 'tel')->ignore($user->id)],
            'role'     => ['required', Rule::in($this->roles()->all())],
        ]);

        // Seul un admin peut attribuer ou retirer le rôle admin
        if ($data['role'] === 'admin' || $user->hasRole('admin')) {
            $this->authorizeRoleAssignment('admin');
        }

        if ($user->id === auth()->id() && $user->hasRole('admin') && $data['role'] !== 'admin') {
            return redirect()->route('admin.users.index')
                ->with('error', 'Vous ne pouvez pas retirer votre propre rôle administrateur.');
        }

        if ($user->hasRole('admin') && $data['role'] !== 'admin' && User::role('admin')->count() <= 1) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Impossible de retirer le rôle du dernier administrateur.');
        }

        $role = $data['role'];
        unset($data['role']);

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);
        $user->syncRoles($role);

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }
        if ($user->hasRole('admin')) {
            $this->authorizeRoleAssignment('admin');
            if (User::role('admin')->count() <= 1) {
                return redirect()->route('admin.users.index')
                    ->with('error', 'Impossible de supprimer le dernier administrateur.');
            }
        }
        if ($user->orders()->exists() || $user->achats()->exists()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Impossible de supprimer cet utilisateur car il a des commandes ou achats.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé avec succès.');
    }

    /** Évite l'escalade de privilèges : seul un admin manipule le rôle admin */
    private function authorizeRoleAssignment(string $role): void
    {
        if ($role === 'admin' && ! auth()->user()->hasRole('admin')) {
            abort(403, 'Seul un administrateur peut gérer le rôle administrateur.');
        }
    }

    /** '' → null pour l'email, chiffres uniquement pour le téléphone */
    private function normalizeInput(Request $request): void
    {
        $request->merge([
            'email' => $request->filled('email') ? trim($request->email) : null,
            'tel'   => preg_replace('/\D/', '', (string) $request->tel),
        ]);
    }
}