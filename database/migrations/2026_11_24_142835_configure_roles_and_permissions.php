<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

// Doit passer APRÈS la migration create_permission_tables de Spatie (date plus récente).
return new class extends Migration
{
    private const CRUD = ['voir', 'creer', 'modifier', 'supprimer'];

    private function modules(): array{
        return [
            'categories'         => self::CRUD,
            'sous-categories'    => self::CRUD,
            'produits'           => self::CRUD,
            'zones'              => self::CRUD,
            'users'              => self::CRUD,
            'livreurs'           => self::CRUD,
            'fournisseurs'       => self::CRUD,
            'depenses'           => self::CRUD,
            'categorie-depenses' => self::CRUD,
            'tournees'           => self::CRUD,
            'orders'             => ['voir', 'modifier', 'supprimer'],
            'reservations'       => ['voir', 'creer', 'modifier'],
            'achats'             => ['voir', 'creer', 'receptionner', 'annuler'],
            'inventaires'        => ['voir', 'creer', 'valider', 'annuler', 'supprimer'],
            'stock'              => ['voir', 'ajuster'],
            'stock-mouvements'   => ['voir'],
            'banner'             => ['voir', 'modifier'],
            'rapports'           => ['voir'],
            'dashboard'          => ['voir'],
        ];
    }

    public function up(): void{
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $modules = $this->modules();

        Permission::findOrCreate('acces-admin');
        foreach ($modules as $module => $actions) {
            foreach ($actions as $action) {
                Permission::findOrCreate("$module.$action");
            }
        }

        // admin : tout passe via Gate::before (AppServiceProvider)
        Role::findOrCreate('admin');
        Role::findOrCreate('client');
        Role::findOrCreate('fournisseur');

        // Gestionnaire : tout sauf la gestion des utilisateurs
        $gestionnaire = collect($modules)
            ->except('users')
            ->flatMap(fn ($actions, $m) => collect($actions)->map(fn ($a) => "$m.$a"))
            ->push('acces-admin')
            ->all();
        Role::findOrCreate('gestionnaire')->syncPermissions($gestionnaire);

        // Caissière : ventes + consultation
        Role::findOrCreate('caissiere')->syncPermissions([
            'acces-admin',
            'orders.voir', 'orders.modifier',
            'reservations.voir', 'reservations.creer', 'reservations.modifier',
            'produits.voir',
            'stock.voir',
            'depenses.voir', 'depenses.creer',
        ]);

        // Reprise de l'ancienne colonne users.role (user → client)
        if (Schema::hasColumn('users', 'role')) {
            $map = ['admin' => 'admin', 'fournisseur' => 'fournisseur', 'user' => 'client'];

            DB::table('users')->select('id', 'role')->get()->each(function ($row) use ($map) {
                $user = User::find($row->id);
                if ($user && $user->roles()->doesntExist()) {
                    $user->assignRole($map[$row->role] ?? 'client');
                }
            });
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        Role::whereIn('name', ['admin', 'gestionnaire', 'caissiere', 'fournisseur', 'client'])->delete();
        Permission::where('name', 'acces-admin')
            ->orWhere(function ($q) {
                foreach (array_keys($this->modules()) as $m) {
                    $q->orWhere('name', 'like', "$m.%");
                }
            })->delete();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
};