<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Zone extends Model
{
    use HasFactory;
    protected $fillable = [
        'nom',
        'tarif',
        'est_expedition',
    ];

    protected $casts = [
        'est_expedition' => 'boolean',
        'tarif' => 'decimal:2',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
