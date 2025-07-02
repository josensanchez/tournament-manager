<?php

namespace Database\Factories;

use App\Models\States\Player\Registered;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Player>
 */
class PlayerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'state' => Registered::class,
            'email' => $this->faker->unique()->safeEmail(),
        ];
    }
}
