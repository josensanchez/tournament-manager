<?php

namespace Database\Factories;

use App\Models\States\Tournament\Created;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tournament>
 */
class TournamentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
            'state' => Created::class,
            'start_date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'gender' => $this->faker->randomElement(['male', 'female']),
        ];
    }
}
