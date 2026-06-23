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
 
    public function achats(){
        return $this->hasMany(Achat::class);
    }
}