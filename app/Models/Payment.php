<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['ride_id', 'rider_id', 'amount', 'payment_method', 'payment_status', 'transaction_id'];

    public function ride()
    {
        return $this->belongsTo(Ride::class);
    }

    public function rider()
    {
        return $this->belongsTo(User::class, 'rider_id');
    }
}
