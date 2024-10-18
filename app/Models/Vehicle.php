<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $guarded = [];

      public function user(){
        return $this->belongsTo(User::class);
    }
    public function photos(){
        return $this->hasMany(Photo::class);
    }
    // public function prices(){
    //     return $this->belongsTo(PriceSetup::class);
    // }
    public function priceSetup()
    {
        return $this->belongsTo(PriceSetup::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function station() {
        return $this->belongsTo(Station::class);
    }

    public function firstPhoto()
    {
        return $this->hasOne(Photo::class, 'vehicle_id')->orderBy('created_at', 'asc');
    }
}
