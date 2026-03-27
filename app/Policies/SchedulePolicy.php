<?php

namespace App\Policies;

use App\Models\Schedule;
use App\Models\User;

class SchedulePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Doctors, admins, and receptionists can view schedules
        return $user->hasRole(['doctor', 'admin', 'receptionist']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Schedule $schedule): bool
    {
        // Admins and receptionists can view all schedules
        if ($user->hasRole(['admin', 'receptionist'])) {
            return true;
        }

        // Doctors can only view their own schedules
        if ($user->hasRole('doctor')) {
            return $schedule->doctor_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Doctors, admins, and receptionists can create schedules
        return $user->hasRole(['admin', 'receptionist']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Schedule $schedule): bool
    {
        // Admins and receptionists can update any schedules
        if ($user->hasRole(['admin', 'receptionist'])) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Schedule $schedule): bool
    {
        // Admins and receptionists can delete any schedules
        if ($user->hasRole(['admin', 'receptionist'])) {
            return true;
        }

        return false;
    }
}
