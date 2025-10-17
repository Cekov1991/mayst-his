<?php

namespace App\Policies;

use App\Models\Patient;
use App\Models\User;

class PatientPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Admin, doctors, and receptionists can all view patients
        return $user->hasPermissionTo('view-patients');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Patient $patient): bool
    {
        // Must have view-patients permission
        if (! $user->hasPermissionTo('view-patients')) {
            return false;
        }

        // Admins can view all patients
        if ($user->hasRole('admin')) {
            return true;
        }

        // Receptionists can view all patients (for scheduling)
        if ($user->hasRole('receptionist')) {
            return true;
        }

        // Doctors can only view patients they have visits with
        if ($user->hasPermissionTo('view-own-data-only')) {
            return $patient->visits()->where('doctor_id', $user->id)->exists();
        }

        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Doctors and receptionists can create patients
        return $user->hasPermissionTo('create-patients');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Patient $patient): bool
    {
        // Must have edit permission
        if (! $user->hasPermissionTo('edit-patients')) {
            return false;
        }

        // Admins can edit all patients
        if ($user->hasRole('admin')) {
            return true;
        }

        // Receptionists can edit all patients
        if ($user->hasRole('receptionist')) {
            return true;
        }

        // Doctors can only edit patients they have visits with
        if ($user->hasPermissionTo('view-own-data-only')) {
            return $patient->visits()->where('doctor_id', $user->id)->exists();
        }

        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Patient $patient): bool
    {
        // Only admins can delete patients
        return $user->hasRole('admin') && $user->hasPermissionTo('delete-patients');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Patient $patient): bool
    {
        // Only admins can restore patients
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Patient $patient): bool
    {
        // Only admins can force delete patients
        return $user->hasRole('admin');
    }
}
