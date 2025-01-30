<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    use HasFactory;
    protected $fillable = [
        'stationName',
        'slug',
        'location_id'
    ];

    public function location(){
        return $this->belongsTo(Location::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function vehicles(){
        return $this->hasMany(Vehicle::class);
    }

    public function relatedPriceSetups()
    {
        return $this->belongsTo(PriceSetup::class, 'price_setup_id')
            ->whereHas('related', function($query) {
                $query->whereColumn('price_setups.id', '!=', 'price_setups.price_setup_id');
            });
    }
}
