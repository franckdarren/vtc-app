<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class DriverRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'ride_id',
        'driver_id',
        'rider_id',
        'rating',
        'comment',
    ];

    // Relation avec Ride
    public function ride()
    {
        return $this->belongsTo(Ride::class);
    }

    // Relation avec Driver
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    // Relation avec Rider (utilisateur)
    public function rider()
    {
        return $this->belongsTo(User::class, 'rider_id');
    }
}
