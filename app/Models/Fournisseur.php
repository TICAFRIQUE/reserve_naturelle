<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class Fournisseur extends Model
{
    use HasFactory;
 
    protected $fillable = [
        'nom',
        'prenom',
        'tel',
        'adress',
        'ville',
        'date_ajout',
    ];

    //Les relations
    
    public function achats(){
        return $this->hasMany(Achat::class);
    }
     /**
     * Accesseur pour le nom complet
     */
    public function getFullNameAttribute(){
        return trim($this->prenom . ' ' . $this->nom);
    }
}