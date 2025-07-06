<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceSetup extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'slug',
        'duration',
        'amount'
    ];

    // public function vehicles()
    // {
    //     return $this->hasMany(Vehicle::class);
    // }
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'price_setup_id', 'category_id');
    }
    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function related()
    {
        return $this->hasMany(PriceSetup::class, 'category_id', 'category_id');
    }
    public function duration()
    {
        return $this->hasOne(Duration::class, 'slug', 'slug');
    }
    public function durationRelation()
    {
        return $this->hasOne(Duration::class, 'slug', 'slug');
    }


}
