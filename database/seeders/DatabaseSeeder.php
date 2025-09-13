<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\User;
use App\Models\Visit;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🏥 Seeding HIS Demo Users...');

        // Create demo users for each role
        $admin = User::factory()->withPersonalTeam()->create([
            'name' => 'Admin User',
            'email' => 'admin@his.local',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $doctor = User::factory()->withPersonalTeam()->create([
            'name' => 'Dr. Stefan Cekov',
            'email' => 'doctor@his.local',
            'password' => Hash::make('password'),
            'role' => 'doctor',
            'is_active' => true,
        ]);

        $doctor2 = User::factory()->withPersonalTeam()->create([
            'name' => 'Dr. Ana Petkovska',
            'email' => 'doctor2@his.local',
            'password' => Hash::make('password'),
            'role' => 'doctor',
            'is_active' => true,
        ]);

        $reception = User::factory()->withPersonalTeam()->create([
            'name' => 'Marija Receptionist',
            'email' => 'reception@his.local',
            'password' => Hash::make('password'),
            'role' => 'reception',
            'is_active' => true,
        ]);

        $tech = User::factory()->withPersonalTeam()->create([
            'name' => 'Petar Technician',
            'email' => 'tech@his.local',
            'password' => Hash::make('password'),
            'role' => 'tech',
            'is_active' => true,
        ]);

        $this->command->info('✅ Created demo users:');
        $this->command->line("   👑 Admin: admin@his.local / password");
        $this->command->line("   👨‍⚕️ Doctor: doctor@his.local / password");
        $this->command->line("   👩‍⚕️ Doctor 2: doctor2@his.local / password");
        $this->command->line("   📋 Reception: reception@his.local / password");
        $this->command->line("   🔬 Tech: tech@his.local / password");

        $this->command->info('🏥 Seeding Sample Patients & Visits...');

        // Create sample patients
        $patients = Patient::factory(15)->create();

        // Create various visit states for testing
        Visit::factory(3)->todayArrived()->create([
            'doctor_id' => $doctor->id,
            'patient_id' => $patients->random()->id,
        ]);

        Visit::factory(2)->todayInProgress()->create([
            'doctor_id' => $doctor->id,
            'patient_id' => $patients->random()->id,
        ]);

        Visit::factory(2)->todayArrived()->create([
            'doctor_id' => $doctor2->id,
            'patient_id' => $patients->random()->id,
        ]);

        Visit::factory(1)->todayInProgress()->create([
            'doctor_id' => $doctor2->id,
            'patient_id' => $patients->random()->id,
        ]);

        // Create scheduled visits for today and coming days
        Visit::factory(10)->create([
            'doctor_id' => $doctor->id,
            'patient_id' => fn() => $patients->random()->id,
            'status' => 'scheduled',
            'scheduled_at' => fake()->dateTimeBetween('today', '+7 days'),
        ]);

        Visit::factory(8)->create([
            'doctor_id' => $doctor2->id,
            'patient_id' => fn() => $patients->random()->id,
            'status' => 'scheduled',
            'scheduled_at' => fake()->dateTimeBetween('today', '+7 days'),
        ]);

        // Create some completed visits for history
        for ($i = 0; $i < 20; $i++) {
            $scheduledAt = fake()->dateTimeBetween('-30 days', '-1 day');
            $arrivedAt = fake()->dateTimeBetween($scheduledAt, $scheduledAt->format('Y-m-d H:i:s') . ' +1 hour');
            $startedAt = fake()->dateTimeBetween($arrivedAt, $arrivedAt->format('Y-m-d H:i:s') . ' +30 minutes');
            $completedAt = fake()->dateTimeBetween($startedAt, $startedAt->format('Y-m-d H:i:s') . ' +2 hours');

            Visit::factory()->create([
                'doctor_id' => [$doctor->id, $doctor2->id][array_rand([$doctor->id, $doctor2->id])],
                'patient_id' => $patients->random()->id,
                'status' => 'completed',
                'scheduled_at' => $scheduledAt,
                'arrived_at' => $arrivedAt,
                'started_at' => $startedAt,
                'completed_at' => $completedAt,
            ]);
        }

        $this->command->info('✅ Created:');
        $this->command->line('   👥 15 sample patients');
        $this->command->line('   📅 ' . Visit::today()->count() . ' visits for today (queue testing)');
        $this->command->line('   📆 ' . Visit::where('status', 'scheduled')->count() . ' upcoming scheduled visits');
        $this->command->line('   ✅ ' . Visit::where('status', 'completed')->count() . ' completed visits (history)');

        $this->command->info('🎉 HIS Demo Data Seeding Complete!');
    }
}
