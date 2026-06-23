<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class SousCategory extends Model
{
    use HasFactory;
 
    protected $table = 'sous_categories';
 
    protected $fillable = [
        'nom',
        'statut',
        'ordre',
        'description',
        'category_id',
    ];
 
    public function category(){
        return $this->belongsTo(Category::class);
    }
}