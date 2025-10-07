<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->firstName().' '.fake()->lastName(),
            'matricule' => strtoupper(fake()->lexify('??')).fake()->unique()->numberBetween(1000, 9999),
            'date_of_birth' => fake()->date(),
            'place_of_birth' => fake()->country(),
            'situation' => fake()->randomElement(['NR', 'R']),
            'gender' => fake()->randomElement(['M', 'F']),
            'photo' => null,
            'classe_id' => fake()->numberBetween(1, 20),
        ];
    }
}
