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
     * Display a listing of slots.
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Slot::class);

        return view('slots.index');
    }

    /**
     * Show the form for creating new slots.
     */
    public function create(): View
    {
        $this->authorize('create', Slot::class);

        $doctors = null;
        if (Auth::user()->hasRole(['admin', 'receptionist'])) {
            $doctors = User::role('doctor')
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
        }

        return view('slots.create', compact('doctors'));
    }

    /**
     * Store newly created slots.
     */
    public function store(StoreSlotRequest $request): RedirectResponse
    {
        $this->authorize('create', Slot::class);

        $validated = $request->validated();

        $dateFrom = Carbon::parse($validated['date_from']);
        $dateTo = Carbon::parse($validated['date_to']);
        $timeFrom = Carbon::parse($validated['time_from']);
        $timeTo = Carbon::parse($validated['time_to']);
        $daysOfWeek = $validated['days_of_week'];

        $slotsToInsert = [];
        $createdCount = 0;

        // Loop through each date from date_from to date_to
        $currentDate = $dateFrom->copy();
        while ($currentDate->lte($dateTo)) {
            $dayOfWeek = $currentDate->dayOfWeek; // 0 = Sunday, 6 = Saturday

            // Check if this day of week is in the selected days
            if (in_array($dayOfWeek, $daysOfWeek)) {
                // Generate 30-minute slots for this day
                $slotStart = $currentDate->copy()->setTimeFromTimeString($timeFrom->format('H:i'));
                $slotEnd = $slotStart->copy()->addMinutes(30);

                while ($slotEnd->lte($currentDate->copy()->setTimeFromTimeString($timeTo->format('H:i')))) {
                    $slotsToInsert[] = [
                        'doctor_id' => $validated['doctor_id'],
                        'start_time' => $slotStart->format('Y-m-d H:i:s'),
                        'end_time' => $slotEnd->format('Y-m-d H:i:s'),
                        'status' => 'available',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    $slotStart->addMinutes(30);
                    $slotEnd->addMinutes(30);
                }
            }

            $currentDate->addDay();
        }

        // Bulk insert, ignoring duplicates
        if (! empty($slotsToInsert)) {
            try {
                DB::table('slots')->insertOrIgnore($slotsToInsert);
                $createdCount = count($slotsToInsert);
            } catch (\Exception $e) {
                // If there are duplicates, insert them one by one
                foreach ($slotsToInsert as $slotData) {
                    try {
                        DB::table('slots')->insert($slotData);
                        $createdCount++;
                    } catch (\Exception $e) {
                        // Skip duplicates
                        continue;
                    }
                }
            }
        }

        return redirect()->route('slots.index')
            ->with('success', __('slots.messages.created_successfully', ['count' => $createdCount]));
    }

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
