<?php

namespace App\Policies;

use App\Models\Slot;
use App\Models\User;

class SlotPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Doctors, admins, and receptionists can view slots
        return $user->hasRole(['doctor', 'admin', 'receptionist']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Slot $slot): bool
    {
        // Admins and receptionists can view all slots
        if ($user->hasRole(['admin', 'receptionist'])) {
            return true;
        }

        // Doctors can only view their own slots
        if ($user->hasRole('doctor')) {
            return $slot->doctor_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Doctors, admins, and receptionists can create slots
        return $user->hasRole(['doctor', 'admin', 'receptionist']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Slot $slot): bool
    {
        // Admins and receptionists can update any slots
        if ($user->hasRole(['admin', 'receptionist'])) {
            return true;
        }

        // Doctors can only update their own slots
        if ($user->hasRole('doctor')) {
            return $slot->doctor_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Slot $slot): bool
    {
        // Admins and receptionists can delete any slots
        if ($user->hasRole(['admin', 'receptionist'])) {
            return true;
        }

        // Doctors can only delete their own slots
        if ($user->hasRole('doctor')) {
            return $slot->doctor_id === $user->id;
        }

        return false;
    }
}
