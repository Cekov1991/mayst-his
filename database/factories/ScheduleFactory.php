<?php

namespace Database\Factories;

use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Schedule>
 */
class ScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $validFrom = Carbon::now()->startOfMonth();
        $validTo = $validFrom->copy()->endOfMonth();

        return [
            'doctor_id' => User::factory(),
            'name' => fake()->optional()->words(3, true),
            'valid_from' => $validFrom,
            'valid_to' => $validTo,
            'start_time' => Carbon::parse('09:00'),
            'end_time' => Carbon::parse('17:00'),
            'slot_interval' => fake()->randomElement([15, 30, 45, 60]),
            'days_of_week' => [1, 2, 3, 4, 5], // Monday to Friday
            'week_pattern' => null,
            'specific_dates' => null,
            'excluded_dates' => null,
            'is_active' => true,
        ];
    }

    /**
     * Indicate that the schedule should be inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the schedule should have a week pattern.
     */
    public function withWeekPattern(array $weeks): static
    {
        return $this->state(fn (array $attributes) => [
            'week_pattern' => $weeks,
        ]);
    }

    /**
     * Indicate that the schedule should have specific dates.
     */
    public function withSpecificDates(array $dates): static
    {
        return $this->state(fn (array $attributes) => [
            'specific_dates' => $dates,
        ]);
    }

    /**
     * Indicate that the schedule should have excluded dates.
     */
    public function withExcludedDates(array $dates): static
    {
        return $this->state(fn (array $attributes) => [
            'excluded_dates' => $dates,
        ]);
    }
}
