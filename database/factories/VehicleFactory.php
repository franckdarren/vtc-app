<?php

namespace Database\Factories;

use App\Models\Driver;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Vehicle::class;

    public function definition(): array
    {
        return [
            'driver_id' => Driver::factory(), // Crée un conducteur associé
            'marque' => $this->faker->randomElement([
                'Toyota',
                'Ford',
                'Honda',
                'Chevrolet',
                'Mercedes-Benz',
                'BMW',
                'Volkswagen',
                'Audi',
                'Nissan',
                'Hyundai',
                'Kia',
                'Peugeot',
                'Renault',
                'Fiat',
                'Mazda',
                'Subaru',
                'Jeep',
                'Lexus',
                'Porsche',
                'Land Rover'
            ]),
            'model' => $this->faker->randomElement([
                'Camry',
                'Civic',
                'F-150',
                'Impala',
                'S-Class',
                '3 Series',
                'Golf',
                'A4',
                'Altima',
                'Elantra',
                'Sorento',
                '208',
                'Clio',
                '500',
                'CX-5',
                'Outback',
                'Wrangler',
                'RX',
                'Cayenne',
                'Range Rover'
            ]),
            'year' => $this->faker->year($max = 'now'), // Année du véhicule (jusqu'à l'année actuelle)
            'license_plate' => $this->faker->unique()->bothify('??###??'), // Plaque d'immatriculation unique
            'color' => $this->faker->safeColorName, // Couleur du véhicule
        ];
    }
}
