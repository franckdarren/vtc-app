<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */

class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */

    protected $model = User::class;

    protected static ?string $password = null;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Définir un rôle aléatoire entre 'client' et 'conducteur'
        $role = $this->faker->randomElement(['client', 'conducteur']);

        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // mot de passe par défaut pour les tests
            'role' => $role,
            'remember_token' => Str::random(10),
            'current_team_id' => null,
            'profile_photo_path' => null,
        ];
    }

    // Factory pour un client
    public function client()
    {
        return $this->state(fn() => [
            'role' => 'client',
        ]);
    }

    // Factory pour un conducteur
    public function conducteur()
    {
        return $this->state(fn() => [
            'role' => 'conducteur',
        ]);
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the user should have a personal team.
     */
    public function withPersonalTeam(?callable $callback = null): static
    {
        if (! Features::hasTeamFeatures()) {
            return $this->state([]);
        }

        return $this->has(
            Team::factory()
                ->state(fn(array $attributes, User $user) => [
                    'name' => $user->name . '\'s Team',
                    'user_id' => $user->id,
                    'personal_team' => true,
                ])
                ->when(is_callable($callback), $callback),
            'ownedTeams'
        );
    }
}
