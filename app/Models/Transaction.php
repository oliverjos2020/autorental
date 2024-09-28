<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'transaction_id',
        'transaction_desc',
        'user_id',
        'amount',
        'response_code',
        'response_message',
        'raw_json',
        'status',
        'booking_id'
    ];
}
