<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Visit;

class VisitPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Doctors and receptionists can view visits
        return $user->hasPermissionTo('view-visits');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Visit $visit): bool
    {
        // Must have view-visits permission
        if (! $user->hasPermissionTo('view-visits')) {
            return false;
        }

        // Admins can view all visits
        if ($user->hasRole('admin')) {
            return true;
        }

        // Receptionists can view all visits
        if ($user->hasRole('receptionist')) {
            return true;
        }

        // Doctors can only view their own visits
        if ($user->hasPermissionTo('view-own-data-only')) {
            return $visit->doctor_id === $user->id;
        }

        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Doctors and receptionists can create visits
        return $user->hasPermissionTo('create-visits');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Visit $visit): bool
    {
        // Must have edit permission
        if (! $user->hasPermissionTo('edit-visits')) {
            return false;
        }

        // Admins can edit all visits
        if ($user->hasRole('admin')) {
            return true;
        }

        // Receptionists can edit all visits (for rescheduling)
        if ($user->hasRole('receptionist')) {
            return true;
        }

        // Doctors can only edit their own visits
        if ($user->hasPermissionTo('view-own-data-only')) {
            return $visit->doctor_id === $user->id;
        }

        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Visit $visit): bool
    {
        // Must have delete permission
        if (! $user->hasPermissionTo('delete-visits')) {
            return false;
        }

        // Admins can delete all visits
        if ($user->hasRole('admin')) {
            return true;
        }

        // Doctors can delete their own visits
        if ($user->hasPermissionTo('view-own-data-only')) {
            return $visit->doctor_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Visit $visit): bool
    {
        // Admins and the visit's doctor can restore
        return $user->hasRole('admin') || $visit->doctor_id === $user->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Visit $visit): bool
    {
        // Only admins can force delete visits
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the visit status.
     */
    public function updateStatus(User $user, Visit $visit): bool
    {
        // Must have manage-visit-status permission
        if (! $user->hasPermissionTo('manage-visit-status')) {
            return false;
        }

        // Admins can update any visit status
        if ($user->hasRole('admin')) {
            return true;
        }

        // Receptionists can update any visit status (for check-in)
        if ($user->hasRole('receptionist')) {
            return true;
        }

        // Doctors can only update status of their own visits
        if ($user->hasRole('doctor')) {
            return $visit->doctor_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can access the medical workspace for this visit.
     * This covers: anamnesis, examination, imaging, treatments, prescriptions, spectacles, diagnoses
     */
    public function accessMedicalWorkspace(User $user, Visit $visit): bool
    {
        // Admins can access all medical workspaces
        if ($user->hasRole('admin')) {
            return true;
        }

        // Doctors can only access medical workspace for their own visits
        if ($user->hasRole('doctor')) {
            return $visit->doctor_id === $user->id;
        }

        // Other roles (receptionist, etc.) cannot access medical workspace
        return false;
    }
}
