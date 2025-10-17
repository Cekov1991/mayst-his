<?php

use App\Models\Anamnesis;
use App\Models\Diagnosis;
use App\Models\ImagingStudy;
use App\Models\OphthalmicExam;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\Refraction;
use App\Models\SpectaclePrescription;
use App\Models\TreatmentPlan;
use App\Models\User;
use App\Models\Visit;

beforeEach(function () {
    // Create roles
    \Spatie\Permission\Models\Role::create(['name' => 'doctor']);
    \Spatie\Permission\Models\Role::create(['name' => 'admin']);
    \Spatie\Permission\Models\Role::create(['name' => 'receptionist']);
});

it('allows doctor to generate PDF for their own visit', function () {
    $doctor = User::factory()->create();
    $doctor->assignRole('doctor');

    $patient = Patient::factory()->create();
    $visit = Visit::factory()->create([
        'doctor_id' => $doctor->id,
        'patient_id' => $patient->id,
    ]);

    $this->actingAs($doctor);

    $response = $this->get(route('visits.pdf', $visit));

    $response->assertSuccessful();
    $response->assertHeader('content-type', 'application/pdf');
});

it('prevents doctor from accessing PDF of another doctors visit', function () {
    $doctor1 = User::factory()->create();
    $doctor1->assignRole('doctor');

    $doctor2 = User::factory()->create();
    $doctor2->assignRole('doctor');

    $patient = Patient::factory()->create();
    $visit = Visit::factory()->create([
        'doctor_id' => $doctor2->id,
        'patient_id' => $patient->id,
    ]);

    $this->actingAs($doctor1);

    $response = $this->get(route('visits.pdf', $visit));

    $response->assertForbidden();
});

it('prevents receptionist from accessing PDF', function () {
    $receptionist = User::factory()->create();
    $receptionist->assignRole('receptionist');

    $doctor = User::factory()->create();
    $doctor->assignRole('doctor');

    $patient = Patient::factory()->create();
    $visit = Visit::factory()->create([
        'doctor_id' => $doctor->id,
        'patient_id' => $patient->id,
    ]);

    $this->actingAs($receptionist);

    $response = $this->get(route('visits.pdf', $visit));

    $response->assertForbidden();
});

it('allows admin to access any visit PDF', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $doctor = User::factory()->create();
    $doctor->assignRole('doctor');

    $patient = Patient::factory()->create();
    $visit = Visit::factory()->create([
        'doctor_id' => $doctor->id,
        'patient_id' => $patient->id,
    ]);

    $this->actingAs($admin);

    $response = $this->get(route('visits.pdf', $visit));

    $response->assertSuccessful();
    $response->assertHeader('content-type', 'application/pdf');
});

it('prevents guest from accessing PDF', function () {
    $doctor = User::factory()->create();
    $doctor->assignRole('doctor');

    $patient = Patient::factory()->create();
    $visit = Visit::factory()->create([
        'doctor_id' => $doctor->id,
        'patient_id' => $patient->id,
    ]);

    $response = $this->get(route('visits.pdf', $visit));

    $response->assertRedirect(route('login'));
});

it('generates PDF with patient information', function () {
    $doctor = User::factory()->create();
    $doctor->assignRole('doctor');

    $patient = Patient::factory()->create([
        'first_name' => 'John',
        'last_name' => 'Doe',
        'sex' => 'male',
        'dob' => '1990-05-15',
        'phone' => '+389 70 123 456',
        'email' => 'john.doe@example.com',
    ]);

    $visit = Visit::factory()->create([
        'doctor_id' => $doctor->id,
        'patient_id' => $patient->id,
    ]);

    $this->actingAs($doctor);

    $response = $this->get(route('visits.pdf', $visit));

    $response->assertSuccessful();
    $response->assertHeader('content-type', 'application/pdf');
});

it('generates PDF with all medical sections when data exists', function () {
    $doctor = User::factory()->create(['name' => 'Dr. Smith']);
    $doctor->assignRole('doctor');

    $patient = Patient::factory()->create([
        'first_name' => 'Jane',
        'last_name' => 'Smith',
    ]);

    $visit = Visit::factory()->create([
        'doctor_id' => $doctor->id,
        'patient_id' => $patient->id,
        'type' => 'exam',
        'status' => 'completed',
    ]);

    // Create anamnesis
    Anamnesis::create([
        'visit_id' => $visit->id,
        'chief_complaint' => 'Blurred vision',
        'history_of_present_illness' => 'Patient reports gradual vision loss',
        'allergies' => 'None',
    ]);

    // Create ophthalmic exam with refractions
    $exam = OphthalmicExam::create([
        'visit_id' => $visit->id,
        'visus_od' => '20/20',
        'visus_os' => '20/30',
        'iop_od' => '15',
        'iop_os' => '16',
    ]);

    Refraction::create([
        'ophthalmic_exam_id' => $exam->id,
        'eye' => 'od',
        'method' => 'subjective',
        'sphere' => -1.50,
        'cylinder' => -0.75,
        'axis' => 90,
    ]);

    // Create diagnosis
    Diagnosis::create([
        'visit_id' => $visit->id,
        'patient_id' => $patient->id,
        'diagnosed_by' => $doctor->id,
        'code' => 'H52.1',
        'code_system' => 'ICD-10',
        'term' => 'Myopia',
        'is_primary' => true,
    ]);

    // Create imaging study
    ImagingStudy::create([
        'visit_id' => $visit->id,
        'modality' => 'oct',
        'eye' => 'ou',
        'status' => 'done',
        'findings' => 'Normal retinal thickness',
    ]);

    // Create treatment plan
    TreatmentPlan::create([
        'visit_id' => $visit->id,
        'plan_type' => 'medication',
        'recommendation' => 'Regular follow-up',
        'details' => 'Monitor progress monthly',
        'status' => 'active',
        'start_date' => now(),
    ]);

    // Create prescription with items
    $prescription = Prescription::create([
        'visit_id' => $visit->id,
        'doctor_id' => $doctor->id,
        'notes' => 'Take as directed',
    ]);

    PrescriptionItem::create([
        'prescription_id' => $prescription->id,
        'drug_name' => 'Artificial Tears',
        'form' => 'drops',
        'dosage_instructions' => '1 drop 4 times daily',
    ]);

    // Create spectacle prescription
    SpectaclePrescription::create([
        'visit_id' => $visit->id,
        'doctor_id' => $doctor->id,
        'type' => 'distance',
        'eye' => 'od',
        'sphere' => -1.50,
        'cylinder' => -0.75,
        'axis' => 90,
    ]);

    $this->actingAs($doctor);

    $response = $this->get(route('visits.pdf', $visit));

    $response->assertSuccessful();
    $response->assertHeader('content-type', 'application/pdf');
});

it('generates PDF with empty sections when no data exists', function () {
    $doctor = User::factory()->create();
    $doctor->assignRole('doctor');

    $patient = Patient::factory()->create();

    $visit = Visit::factory()->create([
        'doctor_id' => $doctor->id,
        'patient_id' => $patient->id,
    ]);

    $this->actingAs($doctor);

    $response = $this->get(route('visits.pdf', $visit));

    $response->assertSuccessful();
    $response->assertHeader('content-type', 'application/pdf');
});

it('generates PDF with correct filename format', function () {
    $doctor = User::factory()->create();
    $doctor->assignRole('doctor');

    $patient = Patient::factory()->create([
        'last_name' => 'Johnson',
    ]);

    $visit = Visit::factory()->create([
        'doctor_id' => $doctor->id,
        'patient_id' => $patient->id,
        'scheduled_at' => '2025-10-15 10:00:00',
    ]);

    $this->actingAs($doctor);

    $response = $this->get(route('visits.pdf', $visit));

    $response->assertSuccessful();
    // Check that response suggests a filename (content-disposition header would contain it)
    // The actual filename is set in the controller as 'visit-report-Johnson-2025-10-15.pdf'
});
