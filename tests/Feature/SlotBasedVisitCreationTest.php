<?php

use App\Models\Patient;
use App\Models\Schedule;
use App\Models\Slot;
use App\Models\User;
use App\Models\Visit;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

beforeEach(function () {
    // Create permissions
    Permission::create(['name' => 'create-visits']);
    Permission::create(['name' => 'edit-visits']);
    Permission::create(['name' => 'delete-visits']);
    Permission::create(['name' => 'view-visits']);
    Permission::create(['name' => 'view-own-data-only']);

    // Create roles
    $doctorRole = Role::create(['name' => 'doctor']);
    $receptionistRole = Role::create(['name' => 'receptionist']);

    // Assign permissions to roles
    $receptionistRole->givePermissionTo(['create-visits', 'edit-visits', 'delete-visits', 'view-visits']);
    $doctorRole->givePermissionTo(['view-visits', 'edit-visits']);

    // Create users
    $this->doctor = User::factory()->create(['is_active' => true]);
    $this->doctor->assignRole('doctor');

    $this->receptionist = User::factory()->create(['is_active' => true]);
    $this->receptionist->assignRole('receptionist');

    // Create patient
    $this->patient = Patient::factory()->create();

    // Create schedule and slot
    $schedule = Schedule::factory()->create([
        'doctor_id' => $this->doctor->id,
        'valid_from' => now()->subDays(10),
        'valid_to' => now()->addDays(30),
        'start_time' => '09:00:00',
        'end_time' => '17:00:00',
        'slot_interval' => 30,
        'is_active' => true,
    ]);

    $this->availableSlot = Slot::create([
        'doctor_id' => $this->doctor->id,
        'schedule_id' => $schedule->id,
        'start_time' => now()->addDays(1)->setTime(10, 0),
        'end_time' => now()->addDays(1)->setTime(10, 30),
        'status' => 'available',
    ]);
});

test('visit can be created with slot', function () {
    $this->actingAs($this->receptionist);

    $visitData = [
        'patient_id' => $this->patient->id,
        'doctor_id' => $this->doctor->id,
        'slot_id' => $this->availableSlot->id,
        'type' => 'exam',
        'status' => 'scheduled',
        'scheduled_at' => $this->availableSlot->start_time->format('Y-m-d\TH:i'),
        'reason_for_visit' => 'Regular checkup',
        'room' => 'Room 101',
    ];

    $response = $this->post(route('visits.store'), $visitData);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    // Verify visit was created
    $this->assertDatabaseHas('visits', [
        'patient_id' => $this->patient->id,
        'doctor_id' => $this->doctor->id,
        'slot_id' => $this->availableSlot->id,
        'type' => 'exam',
        'status' => 'scheduled',
    ]);

    // Verify slot is now booked
    $this->availableSlot->refresh();
    expect($this->availableSlot->status)->toBe('booked');
});

test('visit creation fails with unavailable slot', function () {
    $this->actingAs($this->receptionist);

    // Book the slot first
    $this->availableSlot->update(['status' => 'booked']);

    $visitData = [
        'patient_id' => $this->patient->id,
        'doctor_id' => $this->doctor->id,
        'slot_id' => $this->availableSlot->id,
        'type' => 'exam',
        'status' => 'scheduled',
        'reason_for_visit' => 'Regular checkup',
    ];

    $response = $this->post(route('visits.store'), $visitData);

    $response->assertSessionHasErrors(['slot_id']);
});

test('visit creation fails with slot belonging to different doctor', function () {
    $this->actingAs($this->receptionist);

    $otherDoctor = User::factory()->create(['is_active' => true]);
    $otherDoctor->assignRole('doctor');

    $visitData = [
        'patient_id' => $this->patient->id,
        'doctor_id' => $otherDoctor->id, // Different doctor
        'slot_id' => $this->availableSlot->id, // Slot belongs to $this->doctor
        'type' => 'exam',
        'status' => 'scheduled',
        'reason_for_visit' => 'Regular checkup',
    ];

    $response = $this->post(route('visits.store'), $visitData);

    $response->assertSessionHasErrors(['slot_id']);
});

test('visit deletion releases slot', function () {
    $this->actingAs($this->receptionist);

    // Create a visit
    $visit = Visit::factory()->create([
        'patient_id' => $this->patient->id,
        'doctor_id' => $this->doctor->id,
        'slot_id' => $this->availableSlot->id,
    ]);

    // Mark slot as booked
    $this->availableSlot->update(['status' => 'booked']);

    // Delete the visit
    $response = $this->delete(route('visits.destroy', $visit));

    $response->assertRedirect(route('visits.index'));
    $response->assertSessionHas('success');

    // Verify slot is now available
    $this->availableSlot->refresh();
    expect($this->availableSlot->status)->toBe('available');
});

test('visit cancellation releases slot', function () {
    $this->actingAs($this->receptionist);

    // Create a visit with booked slot
    $visit = Visit::factory()->create([
        'patient_id' => $this->patient->id,
        'doctor_id' => $this->doctor->id,
        'slot_id' => $this->availableSlot->id,
        'status' => 'scheduled',
    ]);

    $this->availableSlot->update(['status' => 'booked']);

    // Cancel the visit
    $response = $this->put(route('visits.update', $visit), [
        'doctor_id' => $this->doctor->id,
        'type' => 'exam',
        'status' => 'cancelled', // Changed to cancelled
        'reason_for_visit' => 'Regular checkup',
    ]);

    $response->assertRedirect();

    // Verify slot is now available
    $this->availableSlot->refresh();
    expect($this->availableSlot->status)->toBe('available');
});

test('scheduled_at is automatically set from slot', function () {
    $this->actingAs($this->receptionist);

    $visitData = [
        'patient_id' => $this->patient->id,
        'doctor_id' => $this->doctor->id,
        'slot_id' => $this->availableSlot->id,
        'type' => 'exam',
        'status' => 'scheduled',
        'reason_for_visit' => 'Regular checkup',
        // Not providing scheduled_at
    ];

    $response = $this->post(route('visits.store'), $visitData);

    $response->assertRedirect();

    // Verify visit was created with correct scheduled_at
    $visit = Visit::where('slot_id', $this->availableSlot->id)->first();
    expect($visit)->not->toBeNull();
    expect($visit->scheduled_at->format('Y-m-d H:i:s'))
        ->toBe($this->availableSlot->start_time->format('Y-m-d H:i:s'));
});
