<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    // 'role' retiré : les rôles sont gérés par Spatie (syncRoles / assignRole)
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'tel',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function booted(): void{
        // Toute nouvelle inscription reçoit le rôle "client"
        static::created(function (User $user) {
            if ($user->roles()->doesntExist()) {
                $user->assignRole('client');
            }
        });
    }

    protected function tel(): Attribute{
        return Attribute::make(
            get: fn ($value) => str_pad((string) $value, 10, '0', STR_PAD_LEFT),
        );
    }

    /** Compatibilité : $user->role renvoie le nom du rôle Spatie (admin, gestionnaire, caissiere, fournisseur, client) */
    protected function role(): Attribute{
        return Attribute::make(
            get: fn () => $this->roles->first()?->name,
        );
    }
    public function isAdmin(): bool{
        return $this->hasRole('admin');
    }
    public function isFournisseur(): bool{
        return $this->hasRole('fournisseur');
    }

    public function isUser(): bool{
        return $this->hasRole('client');
    }

    /** Peut entrer dans l'espace admin (admin, gestionnaire, caissière…) */
    public function isStaff(): bool{
        return $this->can('acces-admin');
    }

    private const ROLE_STYLES = [
        'admin'        => ['bg' => '#2d5a27', 'text' => 'white',   'icon' => 'fa-user-shield', 'avatar' => '#2d5a27'],
        'gestionnaire' => ['bg' => '#1565c0', 'text' => 'white',   'icon' => 'fa-user-tie',    'avatar' => '#1565c0'],
        'caissiere'    => ['bg' => '#8e44ad', 'text' => 'white',   'icon' => 'fa-cash-register','avatar' => '#8e44ad'],
        'fournisseur'  => ['bg' => '#b8860b', 'text' => 'white',   'icon' => 'fa-truck',       'avatar' => '#b8860b'],
        'client'       => ['bg' => '#e8e0d5', 'text' => '#2d5a27', 'icon' => 'fa-user',        'avatar' => '#6c757d'],
    ];

    /** Style d'affichage du rôle (couleurs + icône) pour les vues admin */
    public function getRoleStyleAttribute(): array{
        return self::styleForRole($this->role);
    }

    public static function styleForRole(?string $role): array{
        return self::ROLE_STYLES[$role] ?? ['bg' => '#6c757d', 'text' => 'white', 'icon' => 'fa-user-tag', 'avatar' => '#6c757d'];
    }

    /** Libellé français ; rôle créé depuis le back-office : "chef-de-caisse" -> "Chef de caisse" */
    public static function roleLabel(?string $role): string{
        if (! $role) {
            return '—';
        }
        $key = 'permissions.roles.' . $role;

        return \Illuminate\Support\Facades\Lang::has($key)
            ? __($key)
            : ucfirst(str_replace('-', ' ', $role));
    }

    /** Libellé français du rôle (lang/fr/permissions.php) */
    public function getRoleLabelAttribute(): string{
        return self::roleLabel($this->role);
    }

    /** Première page admin accessible selon les permissions (redirection après login) */
    public function adminHomeRoute(): string{
        $candidates = [
            'dashboard.voir'    => 'admin.dashboard',
            'orders.voir'       => 'admin.orders.index',
            'reservations.voir' => 'admin.reservations.index',
            'produits.voir'     => 'admin.produits.index',
            'stock.voir'        => 'admin.stock.index',
            'depenses.voir'     => 'admin.depenses.index',
        ];
        foreach ($candidates as $perm => $route) {
            if ($this->can($perm)) {
                return $route;
            }
        }
        return 'home';
    }

    public function getFullNameAttribute(){
        return $this->nom . ' ' . $this->prenom;
    }
    public function carts(){
        return $this->hasMany(Cart::class);
    }
    public function cart(){
        return $this->hasOne(Cart::class);
    }
    public function achats(){
        return $this->hasMany(Achat::class);
    }
    public function orders(){
        return $this->hasMany(Order::class);
    }
}