<?php

namespace App\Observers;

use App\Models\Schedule;
use App\Models\Slot;

class ScheduleObserver
{
    /**
     * Handle the Schedule "deleting" event.
     */
    public function deleting(Schedule $schedule): void
    {
        // Delete all non-booked slots associated with this schedule
        Slot::where('schedule_id', $schedule->id)
            ->where('status', '!=', 'booked')
            ->delete();

        // Set schedule_id to null for booked slots (they should remain)
        Slot::where('schedule_id', $schedule->id)
            ->where('status', 'booked')
            ->update(['schedule_id' => null]);
    }
}
