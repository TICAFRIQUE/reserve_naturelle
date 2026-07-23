<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tournee extends Model
{
    use HasFactory;

    protected $fillable = [
        'livreur_id',
        'zone_id',
        'date_tournee',
        'statut',
    ];

    protected $casts = [
        'date_tournee' => 'date',
    ];

    public function livreur(){
        return $this->belongsTo(Livreur::class);
    }

    public function zone(){
        return $this->belongsTo(Zone::class);
    }

    public function orders(){
        return $this->belongsToMany(Order::class, 'tournee_order');
    }

    public function toutesLivrees(): bool{
        return $this->orders()->where('statut', '!=', 'livree')->doesntExist();
    }
}