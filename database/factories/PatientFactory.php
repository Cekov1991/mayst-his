<?php

namespace Database\Factories;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'sex' => fake()->randomElement(['male', 'female']),
            'dob' => fake()->dateTimeBetween('-90 years', '-18 years')->format('Y-m-d'),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->optional(0.7)->safeEmail(),
            'address' => fake()->optional(0.8)->streetAddress(),
            'city' => fake()->optional(0.9)->city(),
            'country' => fake()->optional(0.9)->country(),
            'unique_master_citizen_number' => fake()->optional(0.6)->numerify('############'),
            'notes' => fake()->optional(0.3)->sentence(),
        ];
    }
}
