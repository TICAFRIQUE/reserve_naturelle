<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
 
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
        return $this->prenom . ' ' . $this->nom;
    }

    public function carts(){
        return $this->hasMany(Cart::class);
    }
 
    public function achats(){
        return $this->hasMany(Achat::class);
    }
 
    public function orders(){
        return $this->hasMany(Order::class);
    }
 
    public function ajustements(){
        return $this->hasMany(Ajustement::class);
    }
}
 