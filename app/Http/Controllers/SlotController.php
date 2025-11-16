<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSlotRequest;
use App\Models\Slot;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SlotController extends Controller
{
    /**
     * Remove the specified slot from storage.
     */
    public function destroy(Request $request, Slot $slot): RedirectResponse
    {
        $this->authorize('delete', $slot);

        $applyToAll = $request->boolean('apply_to_all_future');

        if ($applyToAll) {
            // Delete all future slots with the same day of week and time
            // Carbon dayOfWeek: 0=Sunday, 6=Saturday
            // MySQL DAYOFWEEK: 1=Sunday, 7=Saturday
            $dayOfWeek = $slot->start_time->dayOfWeek;
            $mysqlDayOfWeek = $dayOfWeek === 0 ? 1 : $dayOfWeek + 1;
            $time = $slot->start_time->format('H:i:s');

            $query = Slot::where('doctor_id', $slot->doctor_id)
                ->whereRaw('DAYOFWEEK(start_time) = ?', [$mysqlDayOfWeek])
                ->whereTime('start_time', $time)
                ->where('start_time', '>=', $slot->start_time);

            // Only delete available or blocked slots, not booked ones
            $query->whereIn('status', ['available', 'blocked']);

            $deletedCount = $query->count();
            $query->delete();
        } else {
            // Only delete if not booked or if booked but no visit linked
            if ($slot->isBooked() && $slot->visit) {
                return redirect()->back()
                    ->with('error', __('slots.messages.cannot_delete_booked'));
            }

            $slot->delete();
            $deletedCount = 1;
        }

        return redirect()->back()
            ->with('success', __('slots.messages.deleted_successfully', ['count' => $deletedCount]));
    }

    /**
     * Update slot status.
     */
    public function updateStatus(Request $request, Slot $slot): RedirectResponse
    {
        $this->authorize('update', $slot);

        $request->validate([
            'status' => 'required|in:available,blocked',
            'apply_to_all_future' => 'sometimes|boolean',
        ]);

        $newStatus = $request->input('status');
        $applyToAll = $request->boolean('apply_to_all_future');

        if ($applyToAll) {
            // Update all future slots with the same day of week and time
            // Carbon dayOfWeek: 0=Sunday, 6=Saturday
            // MySQL DAYOFWEEK: 1=Sunday, 7=Saturday
            $dayOfWeek = $slot->start_time->dayOfWeek;
            $mysqlDayOfWeek = $dayOfWeek === 0 ? 1 : $dayOfWeek + 1;
            $time = $slot->start_time->format('H:i:s');

            $query = Slot::where('doctor_id', $slot->doctor_id)
                ->whereRaw('DAYOFWEEK(start_time) = ?', [$mysqlDayOfWeek])
                ->whereTime('start_time', $time)
                ->where('start_time', '>=', $slot->start_time)
                ->where('status', '!=', 'booked'); // Don't change booked slots

            $updatedCount = $query->count();
            $query->update(['status' => $newStatus]);
        } else {
            // Only update if not booked
            if ($slot->isBooked()) {
                return redirect()->back()
                    ->with('error', __('slots.messages.cannot_update_booked'));
            }

            $slot->update(['status' => $newStatus]);
            $updatedCount = 1;
        }

        return redirect()->back()
            ->with('success', __('slots.messages.status_updated', ['count' => $updatedCount]));
    }
}
