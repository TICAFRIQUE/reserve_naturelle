<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class AchatProduct extends Model
{
    use HasFactory;
 
    protected $table = 'achat_product';
 
    protected $fillable = [
        'achat_id',
        'product_id',
        'qte',
        'prix_unitaire',
    ];
 
    public function achat(){
        return $this->belongsTo(Achat::class);
    }
 
    public function product(){
        return $this->belongsTo(Product::class);
    }
}