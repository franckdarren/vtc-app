<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = ['driver_id', 'make', 'model', 'year', 'license_plate', 'color'];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
