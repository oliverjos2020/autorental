<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $fillable = [
        'user_id',
        'station_id',
        'vehicleMake',
        'vehicleID',
        'vehicleYear',
        'vehicleModel',
        'transmission',
        'doors',
        'airCondition',
        'seats',
        'price_setup_id',
        'status',
        'publication_status',
        'on_trip',
        'moreInfo',
        'keylessEntry',
        'musicPlayer',
        'airBags',
        'fuelCapacity',
        'maxSpeed',
        'maxPower',
        'motor',
        'category_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function photos()
    {
        return $this->hasMany(Photo::class);
    }
    // public function prices(){
    //     return $this->belongsTo(PriceSetup::class);
    // }
    // public function priceSetup()
    // {
    //     return $this->hasMany(PriceSetup::class);
    // }

    // public function priceSetup()
    // {
    //     return $this->belongsTo(PriceSetup::class, 'price_setup_id');
    // }

    public function priceSetup()
    {
        return $this->hasOne(PriceSetup::class, 'category_id', 'price_setup_id');
    }

    public function relatedPriceSetups()
    {
        return $this->belongsTo(PriceSetup::class, 'price_setup_id')
            ->whereHas('related', function ($query) {
                $query->whereColumn('price_setups.id', '!=', 'price_setups.price_setup_id');
            });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    public function firstPhoto()
    {
        return $this->hasOne(Photo::class, 'vehicle_id')->orderBy('created_at', 'asc');
    }
}
