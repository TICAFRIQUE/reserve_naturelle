<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class Inventaire extends Model
{
    use HasFactory;
 
    protected $fillable = [
        'date_debut',
        'date_fin',
        'statut',
        'user_id',
        
    ];
 
    public function category(){
        return $this->belongsTo(Category::class);
    }
 
    public function cartItems(){
        return $this->hasMany(CartItem::class);
    }
 
    public function orderItems(){
        return $this->hasMany(OrderItem::class);
    }
 
    // public function orders(){
    //     return $this->belongsToMany(Order::class, 'order_items');
    // }
 
    public function achats(){
        return $this->belongsToMany(Achat::class, 'achat_product')->withPivot('qte', 'prix_unitaire');
    }
 
    public function inventaires(){
        return $this->belongsToMany(Inventaire::class, 'inventaire_product')->withPivot('qte', 'type_mvt', 'date_mvt', 'h_mvt');
    }
}