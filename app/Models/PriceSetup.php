<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceSetup extends Model
{
    use HasFactory;

    protected $fillable = [
        'item',
        'slug',
        'duration',
        'amount'
    ];

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }
}
