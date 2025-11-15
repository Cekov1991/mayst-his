<?php

use App\Models\Anamnesis;
use App\Models\Diagnosis;
use App\Models\Patient;
use App\Models\User;
use App\Models\Visit;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    // Create roles if they don't exist
    Role::firstOrCreate(['name' => 'doctor']);
    Role::firstOrCreate(['name' => 'admin']);

    // Create a doctor user with necessary permissions
    $this->doctor = User::factory()->create();
    $this->doctor->assignRole('doctor');

    // Create a patient
    $this->patient = Patient::factory()->create();

    // Create previous visit with data
    $this->previousVisit = Visit::factory()->create([
        'patient_id' => $this->patient->id,
        'doctor_id' => $this->doctor->id,
        'status' => 'completed',
    ]);

    // Create current visit
    $this->currentVisit = Visit::factory()->create([
        'patient_id' => $this->patient->id,
        'doctor_id' => $this->doctor->id,
        'status' => 'in_progress',
    ]);
});

test('doctor can access copy selection page', function () {
    $this->actingAs($this->doctor);

    $response = $this->get(route('visits.copy_selection', [$this->currentVisit, $this->previousVisit]));

    $response->assertStatus(200);
    $response->assertViewIs('visits.copy-selection');
    $response->assertViewHas(['visit', 'previousVisit']);
});

test('copy selection page requires same patient', function () {
    $this->actingAs($this->doctor);

    // Create visit for different patient
    $differentPatient = Patient::factory()->create();
    $differentVisit = Visit::factory()->create([
        'patient_id' => $differentPatient->id,
        'doctor_id' => $this->doctor->id,
    ]);

    $response = $this->get(route('visits.copy_selection', [$this->currentVisit, $differentVisit]));

    $response->assertStatus(403);
});

test('can copy medical history data', function () {
    $this->actingAs($this->doctor);

    // Create anamnesis for previous visit
    Anamnesis::create([
        'visit_id' => $this->previousVisit->id,
        'past_medical_history' => 'Test medical history',
        'allergies' => 'Test allergies',
        'medications_current' => 'Test medications',
    ]);

    $response = $this->post(route('visits.process_copy', [$this->currentVisit, $this->previousVisit]), [
        'medical_history' => ['past_medical_history', 'allergies']
    ]);

    $response->assertRedirect(route('visits.show', $this->currentVisit));
    $response->assertSessionHas('success');

    // Check that anamnesis was created for current visit
    $this->currentVisit->refresh();
    expect($this->currentVisit->anamnesis)->not->toBeNull();
    expect($this->currentVisit->anamnesis->past_medical_history)->toBe('Test medical history');
    expect($this->currentVisit->anamnesis->allergies)->toBe('Test allergies');
    expect($this->currentVisit->anamnesis->medications_current)->toBeNull(); // Not selected
});

test('can copy diagnoses', function () {
    $this->actingAs($this->doctor);

    // Create diagnosis for previous visit
    $diagnosis = Diagnosis::create([
        'visit_id' => $this->previousVisit->id,
        'patient_id' => $this->patient->id,
        'diagnosed_by' => $this->doctor->id,
        'is_primary' => true,
        'eye' => 'OD',
        'code' => 'H40.1',
        'code_system' => 'ICD-10',
        'term' => 'Primary open-angle glaucoma',
        'status' => 'confirmed',
    ]);

    $response = $this->post(route('visits.process_copy', [$this->currentVisit, $this->previousVisit]), [
        'diagnoses' => [$diagnosis->id]
    ]);

    $response->assertRedirect(route('visits.show', $this->currentVisit));

    // Check that diagnosis was copied to current visit
    $this->currentVisit->refresh();
    expect($this->currentVisit->diagnoses)->toHaveCount(1);

    $copiedDiagnosis = $this->currentVisit->diagnoses->first();
    expect($copiedDiagnosis->code)->toBe('H40.1');
    expect($copiedDiagnosis->term)->toBe('Primary open-angle glaucoma');
    expect($copiedDiagnosis->notes)->toContain('Copied from previous visit');
});

test('requires at least one selection', function () {
    $this->actingAs($this->doctor);

    $response = $this->post(route('visits.process_copy', [$this->currentVisit, $this->previousVisit]), []);

    $response->assertSessionHasErrors('selection');
});

test('unauthorized user cannot access copy functionality', function () {
    // Create a different doctor
    $otherDoctor = User::factory()->create();
    $otherDoctor->assignRole('doctor');

    $this->actingAs($otherDoctor);

    $response = $this->get(route('visits.copy_selection', [$this->currentVisit, $this->previousVisit]));

    $response->assertStatus(403);
});
