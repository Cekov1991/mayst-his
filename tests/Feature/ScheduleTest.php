<?php

use App\Models\Schedule;
use App\Models\Slot;
use App\Models\User;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'doctor']);
    Role::firstOrCreate(['name' => 'admin']);
    Role::firstOrCreate(['name' => 'receptionist']);

    $this->doctor = User::factory()->create();
    $this->doctor->assignRole('doctor');

    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin');

    $this->receptionist = User::factory()->create();
    $this->receptionist->assignRole('receptionist');
});

test('doctor can view schedules index', function () {
    $this->actingAs($this->doctor);

    $response = $this->get(route('schedules.index'));

    $response->assertSuccessful();
});

test('admin can view all schedules', function () {
    $otherDoctor = User::factory()->create();
    $otherDoctor->assignRole('doctor');

    Schedule::factory()->create(['doctor_id' => $this->doctor->id]);
    Schedule::factory()->create(['doctor_id' => $otherDoctor->id]);

    $this->actingAs($this->admin);

    $response = $this->get(route('schedules.index'));

    $response->assertSuccessful();
    expect(Schedule::count())->toBe(2);
});

test('doctor can only view their own schedules', function () {
    $otherDoctor = User::factory()->create();
    $otherDoctor->assignRole('doctor');

    Schedule::factory()->create(['doctor_id' => $this->doctor->id]);
    Schedule::factory()->create(['doctor_id' => $otherDoctor->id]);

    $this->actingAs($this->doctor);

    $response = $this->get(route('schedules.index'));

    $response->assertSuccessful();
    expect(Schedule::where('doctor_id', $this->doctor->id)->count())->toBe(1);
});

test('doctor can create a schedule', function () {
    $this->actingAs($this->doctor);

    $data = [
        'doctor_id' => $this->doctor->id,
        'name' => 'November Schedule',
        'valid_from' => Carbon::now()->startOfMonth()->format('Y-m-d'),
        'valid_to' => Carbon::now()->endOfMonth()->format('Y-m-d'),
        'start_time' => '09:00',
        'end_time' => '17:00',
        'slot_interval' => 30,
        'days_of_week' => [1, 2, 3, 4, 5],
        'is_active' => true,
    ];

    $response = $this->post(route('schedules.store'), $data);

    $response->assertRedirect(route('schedules.index'));
    expect(Schedule::count())->toBe(1);
    expect(Schedule::first()->name)->toBe('November Schedule');
});

test('schedule creation generates slots', function () {
    $this->actingAs($this->doctor);

    $validFrom = Carbon::now()->startOfMonth();
    $validTo = $validFrom->copy()->addDays(2); // Just 3 days for testing

    $data = [
        'doctor_id' => $this->doctor->id,
        'valid_from' => $validFrom->format('Y-m-d'),
        'valid_to' => $validTo->format('Y-m-d'),
        'start_time' => '09:00',
        'end_time' => '12:00',
        'slot_interval' => 30,
        'days_of_week' => [1, 2], // Monday and Tuesday
        'is_active' => true,
    ];

    $response = $this->post(route('schedules.store'), $data);

    $response->assertRedirect(route('schedules.index'));
    $schedule = Schedule::first();
    expect($schedule->slots->count())->toBeGreaterThan(0);
});

test('schedule with week pattern generates slots correctly', function () {
    $this->actingAs($this->doctor);

    $validFrom = Carbon::now()->startOfMonth();
    $validTo = $validFrom->copy()->endOfMonth();

    $data = [
        'doctor_id' => $this->doctor->id,
        'valid_from' => $validFrom->format('Y-m-d'),
        'valid_to' => $validTo->format('Y-m-d'),
        'start_time' => '09:00',
        'end_time' => '12:00',
        'slot_interval' => 30,
        'days_of_week' => [6], // Saturday
        'week_pattern' => [1, 3], // 1st and 3rd week
        'is_active' => true,
    ];

    $response = $this->post(route('schedules.store'), $data);

    $response->assertRedirect(route('schedules.index'));
    $schedule = Schedule::first();
    expect($schedule->week_pattern)->toBe([1, 3]);
    expect($schedule->slots->count())->toBeGreaterThan(0);
});

test('doctor can update their own schedule', function () {
    $schedule = Schedule::factory()->create(['doctor_id' => $this->doctor->id]);

    $this->actingAs($this->doctor);

    $response = $this->put(route('schedules.update', $schedule), [
        'doctor_id' => $this->doctor->id,
        'name' => 'Updated Schedule',
        'valid_from' => $schedule->valid_from->format('Y-m-d'),
        'valid_to' => $schedule->valid_to->format('Y-m-d'),
        'start_time' => $schedule->start_time->format('H:i'),
        'end_time' => $schedule->end_time->format('H:i'),
        'slot_interval' => $schedule->slot_interval,
        'days_of_week' => $schedule->days_of_week,
        'is_active' => $schedule->is_active,
    ]);

    $response->assertRedirect(route('schedules.index'));
    expect($schedule->fresh()->name)->toBe('Updated Schedule');
});

test('doctor cannot update another doctors schedule', function () {
    $otherDoctor = User::factory()->create();
    $otherDoctor->assignRole('doctor');

    $schedule = Schedule::factory()->create(['doctor_id' => $otherDoctor->id]);

    $this->actingAs($this->doctor);

    $response = $this->put(route('schedules.update', $schedule), [
        'doctor_id' => $otherDoctor->id,
        'name' => 'Updated Schedule',
        'valid_from' => $schedule->valid_from->format('Y-m-d'),
        'valid_to' => $schedule->valid_to->format('Y-m-d'),
        'start_time' => $schedule->start_time->format('H:i'),
        'end_time' => $schedule->end_time->format('H:i'),
        'slot_interval' => $schedule->slot_interval,
        'days_of_week' => $schedule->days_of_week,
        'is_active' => $schedule->is_active,
    ]);

    $response->assertForbidden();
});

test('schedule deletion removes non-booked slots', function () {
    $schedule = Schedule::factory()->create(['doctor_id' => $this->doctor->id]);
    $schedule->generateSlots();

    $slot1 = $schedule->slots->first();
    $slot2 = $schedule->slots->skip(1)->first();

    // Book one slot
    $slot1->update(['status' => 'booked']);

    $this->actingAs($this->doctor);

    $response = $this->delete(route('schedules.destroy', $schedule));

    $response->assertRedirect(route('schedules.index'));
    expect(Schedule::count())->toBe(0);
    expect(Slot::where('schedule_id', $schedule->id)->count())->toBe(0);
    expect(Slot::where('id', $slot1->id)->exists())->toBeTrue();
    expect(Slot::where('id', $slot2->id)->exists())->toBeFalse();
});

test('schedule regeneration removes non-booked slots and recreates them', function () {
    $schedule = Schedule::factory()->create(['doctor_id' => $this->doctor->id]);
    $schedule->generateSlots();

    $slot1 = $schedule->slots->first();
    $slot2 = $schedule->slots->skip(1)->first();

    // Book one slot
    $slot1->update(['status' => 'booked']);
    $originalSlotCount = $schedule->slots->count();

    $this->actingAs($this->doctor);

    $response = $this->put(route('schedules.update', $schedule), [
        'doctor_id' => $this->doctor->id,
        'name' => $schedule->name,
        'valid_from' => $schedule->valid_from->format('Y-m-d'),
        'valid_to' => $schedule->valid_to->format('Y-m-d'),
        'start_time' => $schedule->start_time->format('H:i'),
        'end_time' => $schedule->end_time->format('H:i'),
        'slot_interval' => $schedule->slot_interval,
        'days_of_week' => $schedule->days_of_week,
        'is_active' => $schedule->is_active,
        'regenerate_slots' => true,
    ]);

    $response->assertRedirect(route('schedules.index'));
    $schedule->refresh();
    expect($schedule->slots->count())->toBeGreaterThan(0);
    expect(Slot::where('id', $slot1->id)->exists())->toBeTrue(); // Booked slot remains
    expect(Slot::where('id', $slot2->id)->exists())->toBeFalse(); // Non-booked slot removed
});

test('admin can delete any schedule', function () {
    $schedule = Schedule::factory()->create(['doctor_id' => $this->doctor->id]);

    $this->actingAs($this->admin);

    $response = $this->delete(route('schedules.destroy', $schedule));

    $response->assertRedirect(route('schedules.index'));
    expect(Schedule::count())->toBe(0);
});

test('receptionist can create schedule for any doctor', function () {
    $this->actingAs($this->receptionist);

    $data = [
        'doctor_id' => $this->doctor->id,
        'name' => 'Receptionist Created Schedule',
        'valid_from' => Carbon::now()->startOfMonth()->format('Y-m-d'),
        'valid_to' => Carbon::now()->endOfMonth()->format('Y-m-d'),
        'start_time' => '09:00',
        'end_time' => '17:00',
        'slot_interval' => 30,
        'days_of_week' => [1, 2, 3, 4, 5],
        'is_active' => true,
    ];

    $response = $this->post(route('schedules.store'), $data);

    $response->assertRedirect(route('schedules.index'));
    expect(Schedule::where('doctor_id', $this->doctor->id)->count())->toBe(1);
});
