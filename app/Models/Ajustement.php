<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class Ajustement extends Model
{
    use HasFactory;
 
    protected $fillable = [
        'qte_corriger',
        'qte_apres',
        'qte_avant',
        'date_ajust',
        'h_ajust',
        'description',
        'type',
        'motif',
        'user_id',
        'achat_id',
    ];
 
    protected $casts = [
        'date_ajust' => 'date',
        'h_ajust' => 'datetime',
    ];
 
    public function user(){
        return $this->belongsTo(User::class);
    }
 
    public function achat(){
        return $this->belongsTo(Achat::class);
    }
}