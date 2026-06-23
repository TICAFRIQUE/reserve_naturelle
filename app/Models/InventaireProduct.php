<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class InventaireProduct extends Model
{
    use HasFactory;
 
    protected $fillable = [
        'inventaire_id',
        'product_id',
        'qte',
        'type_mvt',
        'date_mvt',
        'h_mvt',
    ];
 
    protected $casts = [
        'date_mvt' => 'date',
        'h_mvt' => 'datetime',
    ];
 
    public function inventaire(){
        return $this->belongsTo(Inventaire::class);
    }
 
    public function product(){
        return $this->belongsTo(Product::class);
    }
}