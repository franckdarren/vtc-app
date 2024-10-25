<?php

namespace Database\Seeders;

use App\Models\Ride;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Driver;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->count(30)->client()->create();

        User::factory()->count(15)->conducteur()->create();

        Driver::factory()->count(15)->create();

        Vehicle::factory()->count(15)->create();

        // Crée 10 trajets pour les utilisateurs, conducteurs et véhicules
        Ride::factory()->count(100)->create();
    }
}
