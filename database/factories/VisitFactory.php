<?php

namespace Database\Factories;

use App\Models\Patient;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Visit>
 */
class VisitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $scheduledAt = fake()->dateTimeBetween('-30 days', '+30 days');
        $status = fake()->randomElement(['scheduled', 'arrived', 'in_progress', 'completed', 'cancelled']);

        // Set timestamps based on status
        $arrivedAt = null;
        $startedAt = null;
        $completedAt = null;

        if (in_array($status, ['arrived', 'in_progress', 'completed'])) {
            $arrivedAt = fake()->dateTimeBetween($scheduledAt, $scheduledAt->format('Y-m-d H:i:s') . ' +2 hours');
        }

        if (in_array($status, ['in_progress', 'completed'])) {
            $startedAt = fake()->dateTimeBetween($arrivedAt, $arrivedAt->format('Y-m-d H:i:s') . ' +30 minutes');
        }

        if ($status === 'completed') {
            $completedAt = fake()->dateTimeBetween($startedAt, $startedAt->format('Y-m-d H:i:s') . ' +2 hours');
        }

        return [
            'patient_id' => Patient::factory(),
            'doctor_id' => User::factory()->state(['role' => 'doctor']),
            'type' => fake()->randomElement(['exam', 'control', 'surgery']),
            'status' => $status,
            'scheduled_at' => $scheduledAt,
            'arrived_at' => $arrivedAt,
            'started_at' => $startedAt,
            'completed_at' => $completedAt,
            'reason_for_visit' => fake()->optional(0.8)->sentence(),
            'room' => fake()->optional(0.7)->randomElement(['Room 1', 'Room 2', 'Room 3', 'Surgery A', 'Surgery B']),
        ];
    }

    /**
     * Create a visit for today with arrived status
     */
    public function todayArrived(): static
    {
        return $this->state(fn (array $attributes) => [
            'scheduled_at' => now()->setHour(fake()->numberBetween(8, 16))->setMinute(fake()->randomElement([0, 15, 30, 45])),
            'status' => 'arrived',
            'arrived_at' => now()->subMinutes(fake()->numberBetween(5, 60)),
        ]);
    }

    /**
     * Create a visit for today with in_progress status
     */
    public function todayInProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'scheduled_at' => now()->setHour(fake()->numberBetween(8, 16))->setMinute(fake()->randomElement([0, 15, 30, 45])),
            'status' => 'in_progress',
            'arrived_at' => now()->subMinutes(fake()->numberBetween(30, 90)),
            'started_at' => now()->subMinutes(fake()->numberBetween(5, 30)),
        ]);
    }
}
