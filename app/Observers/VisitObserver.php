<?php

namespace App\Observers;

use App\Models\Slot;
use App\Models\Visit;

class VisitObserver
{
    /**
     * Handle the Visit "created" event.
     */
    public function created(Visit $visit): void
    {
        // Mark slot as booked when visit is created (backup mechanism)
        if ($visit->slot_id) {
            $slot = Slot::find($visit->slot_id);
            if ($slot && $slot->isAvailable()) {
                $slot->update(['status' => 'booked']);
            }
        }
    }

    /**
     * Handle the Visit "updated" event.
     */
    public function updated(Visit $visit): void
    {
        // Handle slot changes when visit is updated
        if ($visit->isDirty('slot_id')) {
            $oldSlotId = $visit->getOriginal('slot_id');
            $newSlotId = $visit->slot_id;

            // Release old slot if it exists
            if ($oldSlotId) {
                $oldSlot = Slot::find($oldSlotId);
                if ($oldSlot && $oldSlot->isBooked()) {
                    $oldSlot->update(['status' => 'available']);
                }
            }

            // Book new slot if it exists
            if ($newSlotId) {
                $newSlot = Slot::find($newSlotId);
                if ($newSlot && $newSlot->isAvailable()) {
                    $newSlot->update(['status' => 'booked']);
                }
            }
        }

        // Handle visit cancellation - release slot
        if ($visit->isDirty('status') && $visit->status === 'cancelled') {
            if ($visit->slot_id) {
                $slot = Slot::find($visit->slot_id);
                if ($slot && $slot->isBooked()) {
                    $slot->update(['status' => 'available']);
                }
            }
        }
    }

    /**
     * Handle the Visit "deleted" event.
     */
    public function deleted(Visit $visit): void
    {
        // Release slot when visit is deleted
        if ($visit->slot_id) {
            $slot = Slot::find($visit->slot_id);
            if ($slot && $slot->isBooked()) {
                $slot->update(['status' => 'available']);
            }
        }
    }

    /**
     * Handle the Visit "restored" event.
     */
    public function restored(Visit $visit): void
    {
        // Re-book slot when visit is restored
        if ($visit->slot_id && $visit->status !== 'cancelled') {
            $slot = Slot::find($visit->slot_id);
            if ($slot && $slot->isAvailable()) {
                $slot->update(['status' => 'booked']);
            }
        }
    }

    /**
     * Handle the Visit "force deleted" event.
     */
    public function forceDeleted(Visit $visit): void
    {
        // Release slot when visit is force deleted
        if ($visit->slot_id) {
            $slot = Slot::find($visit->slot_id);
            if ($slot && $slot->isBooked()) {
                $slot->update(['status' => 'available']);
            }
        }
    }
}
