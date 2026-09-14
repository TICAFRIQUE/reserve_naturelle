<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;
 
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
 
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'tel',
        'role',
    ];
 
    protected $hidden = [
        'password',
        'remember_token',
    ];
 
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected function tel(): Attribute{
        return Attribute::make(
            get: fn ($value) => str_pad((string) $value, 10, '0', STR_PAD_LEFT),
        );
    }
    public function isAdmin(){
        return $this->role === "admin";
    }

    // Méthode pour vérifier si l'utilisateur est fournisseur
    public function isFournisseur(){
        return $this->role === 'fournisseur';
    }

    // Méthode pour vérifier si l'utilisateur est user normal ou client
    public function isUser(){
        return $this->role === 'user';
    }

    // Accesseur pour le nom complet
    public function getFullNameAttribute(){
        return $this->nom . ' ' .$this->prenom;
    }

    public function carts(){
        return $this->hasMany(Cart::class);
    }

    //Pour le panier
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
 