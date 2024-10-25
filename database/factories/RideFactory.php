<?php

namespace Database\Factories;

use App\Models\Ride;
use App\Models\User;
use App\Models\Driver;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ride>
 */
class RideFactory extends Factory
{
    protected $model = Ride::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Générer des valeurs de latitude et longitude comprises dans l'estuaire de Libreville
        $latitudeStart = $this->faker->latitude(0.4, 0.5); // Latitude de départ
        $longitudeStart = $this->faker->longitude(9.4, 9.6); // Longitude de départ

        $latitudeEnd = $this->faker->latitude(0.4, 0.5); // Latitude d'arrivée
        $longitudeEnd = $this->faker->longitude(9.4, 9.6); // Longitude d'arrivée

        // Calculer la distance (en km) entre les deux points (méthode à créer)
        $distance = $this->calculateDistance($latitudeStart, $longitudeStart, $latitudeEnd, $longitudeEnd);

        return [
            'rider_id' => User::factory(), // Crée un utilisateur associé comme rider
            'driver_id' => Driver::factory(), // Crée un conducteur associé
            'vehicle_id' => Vehicle::factory(), // Crée un véhicule associé
            'latitude_start_location' => $latitudeStart, // Latitude de la localisation de départ
            'longitude_start_location' => $longitudeStart,
            'latitude_end_location' => $latitudeEnd, // Latitude de la localisation d'arrivée
            'longitude_end_location' => $longitudeEnd,  // Longitude de la localisation d'arrivée
            'status' => $this->faker->randomElement(['pending', 'accepted', 'completed', 'canceled']), // Statut de la course
            'distance' => $distance, // Distance calculée
            'price' => $distance * 100,
            'start_time' => $this->faker->dateTimeBetween('-1 month', 'now'), // Heure de début dans le dernier mois
            'end_time' => $this->faker->dateTimeBetween('now', '+1 month'), // Heure de fin dans le mois à venir
        ];
    }

    /**
     * Calculate the distance between two geographical points.
     *
     * @param float $lat1
     * @param float $lon1
     * @param float $lat2
     * @param float $lon2
     * @return float
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2): float
    {
        $earthRadius = 6371; // Rayon de la Terre en kilomètres

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c; // Retourne la distance en kilomètres
    }
}
