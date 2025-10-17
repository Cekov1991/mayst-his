<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🔐 Creating Route-Based Roles & Permissions...');

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions based on route structure
        $permissions = [
            // Patient Management (lines 34-35)
            'view-patients',
            'create-patients',
            'edit-patients',
            'delete-patients',

            // Visit Management (lines 38-39)
            'view-visits',
            'create-visits',
            'edit-visits',
            'delete-visits',
            'manage-visit-status',

            // Medical Workspace Access (lines 42-87)
            // Covers: anamnesis, examination, imaging, treatments, prescriptions, spectacles, diagnoses
            'access-medical-workspace',

            // Doctor Queue (line 31)
            'access-doctor-queue',

            // Data Scoping
            'view-own-data-only',

            // System Administration
            'manage-users',
        ];

        $this->command->line('   📋 Creating permissions...');
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $this->createAdminRole();
        $this->createDoctorRole();
        $this->createReceptionistRole();
        $this->createPatientRole();

        $this->command->info('✅ Route-Based Permissions created!');
    }

    private function createAdminRole(): void
    {
        $this->command->line('   👑 Creating Admin role...');

        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());
    }

    private function createDoctorRole(): void
    {
        $this->command->line('   👨‍⚕️ Creating Doctor role...');

        $doctor = Role::create(['name' => 'doctor']);
        $doctor->givePermissionTo([
            // Patient & Visit management
            'view-patients',
            'create-patients',
            'edit-patients',
            'view-visits',
            'create-visits',
            'edit-visits',
            'manage-visit-status',

            // Full medical access
            'access-medical-workspace',  // anamnesis, exam, prescriptions, etc.
            'access-doctor-queue',

            // Scoped to own data
            'view-own-data-only',
        ]);
    }

    private function createReceptionistRole(): void
    {
        $this->command->line('   📋 Creating Receptionist role...');

        $receptionist = Role::create(['name' => 'receptionist']);
        $receptionist->givePermissionTo([
            // Can manage patients and visits (scheduling/admin)
            'view-patients',
            'create-patients',
            'edit-patients',
            'view-visits',
            'create-visits',
            'edit-visits',
            'manage-visit-status',

            // NO medical workspace access
            // NO doctor queue access
            // NO data scoping (can see all patients)
        ]);
    }

    private function createPatientRole(): void
    {
        $this->command->line('   🧑‍🤝‍🧑 Creating Patient role...');

        $patient = Role::create(['name' => 'patient']);
        $patient->givePermissionTo([
            'view-own-data-only', // Future: view own appointments/basic info
        ]);
    }
}
