<?php

namespace Database\Factories;

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
        $gender = $this->faker->randomElement(['Masculino', 'Femenino']);
    
        return [
            'name' => $this->faker->name,
            'gender' => $gender,
            'skill_level' => $this->faker->numberBetween(0, 100),
            'strength' => $gender === 'Masculino' ? $this->faker->numberBetween(0, 100) : null,
            'speed' => $gender === 'Masculino' ? $this->faker->numberBetween(0, 100) : null,
            'reaction_time' => $gender === 'Femenino' ? $this->faker->numberBetween(0, 100) : null,
        ];
    }
    
}
