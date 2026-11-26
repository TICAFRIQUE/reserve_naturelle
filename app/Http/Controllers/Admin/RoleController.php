<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /** Rôles système : ni renommés, ni supprimés */
    public const PROTECTED_ROLES = ['admin', 'client', 'fournisseur','gestionnaire'];

    public function index(){
        $roles = Role::withCount(['users', 'permissions'])->orderBy('name')->get();
        return view('admin.roles.index', compact('roles'));
    }

    public function create(){
        return view('admin.roles.form', [
            'role'     => new Role(),
            'groups'   => $this->groupedPermissions(),
            'selected' => [],
        ]);
    }

    public function store(Request $request){
        $request->merge(['name' => Str::slug((string) $request->name)]);

        $data = $request->validate([
            'name'          => ['required', 'string', 'max:50', Rule::unique(config('permission.table_names.roles'), 'name')],
            'permissions'   => ['nullable', 'array'],
            'permissions.*' => ['string', Rule::exists(config('permission.table_names.permissions'), 'name')],
        ], [
            'name.required' => 'Le nom du rôle est obligatoire.',
            'name.unique'   => 'Ce rôle existe déjà.',
        ]);

        $role = Role::create(['name' => $data['name'], 'guard_name' => 'web']);
        $role->syncPermissions($data['permissions'] ?? []);
        return redirect()->route('admin.roles.index')->with('success', 'Rôle créé avec succès.');
    }

    public function edit(Role $role){
        if ($role->name === 'admin') {
            return redirect()->route('admin.roles.index')
                ->with('error', "Le rôle administrateur dispose de tous les droits : il n'est pas modifiable.");
        }

        return view('admin.roles.form', [
            'role'     => $role,
            'groups'   => $this->groupedPermissions(),
            'selected' => $role->permissions->pluck('name')->all(),
        ]);
    }

    public function update(Request $request, Role $role){
        abort_if($role->name === 'admin', 403);
        $locked = in_array($role->name, self::PROTECTED_ROLES, true);

        if (! $locked) {
            $request->merge(['name' => Str::slug((string) $request->name)]);
        }

        $rules = [
            'permissions'   => ['nullable', 'array'],
            'permissions.*' => ['string', Rule::exists(config('permission.table_names.permissions'), 'name')],
        ];
        if (! $locked) {
            $rules['name'] = ['required', 'string', 'max:50', Rule::unique(config('permission.table_names.roles'), 'name')->ignore($role->id)];
        }

        $data = $request->validate($rules, [
            'name.required' => 'Le nom du rôle est obligatoire.',
            'name.unique'   => 'Ce rôle existe déjà.',
        ]);

        if (! $locked) {
            $role->update(['name' => $data['name']]);
        }
        $role->syncPermissions($data['permissions'] ?? []);
        return redirect()->route('admin.roles.index')->with('success', 'Rôle mis à jour avec succès.');
    }

    public function destroy(Role $role){
        if (in_array($role->name, self::PROTECTED_ROLES, true)) {
            return back()->with('error', 'Ce rôle système ne peut pas être supprimé.');
        }
        if ($role->users()->exists()) {
            return back()->with('error', 'Impossible : des utilisateurs ont encore ce rôle. Réattribuez-leur un autre rôle d\'abord.');
        }
        $role->delete();
        return redirect()->route('admin.roles.index')->with('success', 'Rôle supprimé.');
    }

    /** Permissions regroupées par module (hors "acces-admin", géré séparément) */

    private function groupedPermissions(){
        $order = array_keys((array) trans('permissions.modules'));
        return Permission::orderBy('name')->get()
            ->reject(fn ($p) => $p->name === 'acces-admin')
            ->groupBy(fn ($p) => explode('.', $p->name)[0])
            ->sortBy(fn ($perms, $module) => ($i = array_search($module, $order, true)) === false ? 999 : $i);
    }
}