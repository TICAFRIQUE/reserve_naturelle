<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class Sortie extends Model
{
    use HasFactory;
 
    protected $fillable = [
        'qte_sortie',
        'type_sortie',
        'date_sortie',
        'h_sortie',
        'description',
    ];
 
    protected $casts = [
        'date_sortie' => 'date',
        'h_sortie' => 'datetime',
    ];
}
 