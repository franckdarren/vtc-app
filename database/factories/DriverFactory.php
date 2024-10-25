<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Driver;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Driver>
 */
class DriverFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Driver::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->conducteur(), // Crée un utilisateur associé
            'license_number' => $this->faker->unique()->numerify('LICE-#####'), // Format de numéro de permis
            'rating' => $this->faker->randomFloat(1, 0, 5), // Note entre 0 et 5 avec une décimale
            'availability_status' => $this->faker->boolean(70), // 80% de chances que l'état soit disponible
        ];
    }
}
