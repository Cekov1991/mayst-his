<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use App\Models\Schedule;
use App\Models\Slot;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    /**
     * Display a listing of schedules.
     */
    public function index(): View
    {
        $this->authorize('viewAny', Schedule::class);

        $query = Schedule::with(['doctor', 'slots']);

        if (Auth::user()->hasRole('doctor')) {
            $query->where('doctor_id', Auth::id());
        }

        $schedules = $query->orderBy('valid_from', 'desc')
            ->orderBy('valid_to', 'desc')
            ->get()
            ->groupBy(fn ($schedule) => $schedule->valid_from->format('Y-m'));

        return view('schedules.index', compact('schedules'));
    }

    /**
     * Show the form for creating a new schedule.
     */
    public function create(): View
    {
        $this->authorize('create', Schedule::class);

        $doctors = null;
        if (Auth::user()->hasRole(['admin', 'receptionist'])) {
            $doctors = User::role('doctor')
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
        }

        return view('schedules.create', compact('doctors'));
    }

    /**
     * Store a newly created schedule in storage.
     */
    public function store(StoreScheduleRequest $request): RedirectResponse
    {
        $this->authorize('create', Schedule::class);

        $validated = $request->validated();

        $schedule = Schedule::create($validated);

        // Generate slots for the schedule
        $slotsGenerated = $schedule->generateSlots();

        return redirect()->route('schedules.index')
            ->with('success', __('schedules.messages.created_successfully', ['slots_generated' => $slotsGenerated]));
    }

    /**
     * Display the specified schedule.
     */
    public function show(Schedule $schedule): View
    {
        $this->authorize('view', $schedule);

        $schedule->load(['doctor', 'slots.visit.patient']);

        return view('schedules.show', compact('schedule'));
    }

    /**
     * Show the form for editing the specified schedule.
     */
    public function edit(Schedule $schedule): View
    {
        $this->authorize('update', $schedule);

        $doctors = null;
        if (Auth::user()->hasRole(['admin', 'receptionist'])) {
            $doctors = User::role('doctor')
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
        }

        return view('schedules.edit', compact('schedule', 'doctors'));
    }

    /**
     * Update the specified schedule in storage.
     */
    public function update(UpdateScheduleRequest $request, Schedule $schedule): RedirectResponse
    {
        $this->authorize('update', $schedule);

        $validated = $request->validated();

        $regenerateSlots = $request->boolean('regenerate_slots', false);

        // Check if schedule pattern changed (would require regeneration)
        $patternChanged = false;
        if (isset($validated['days_of_week']) && $validated['days_of_week'] !== $schedule->days_of_week) {
            $patternChanged = true;
        }
        if (isset($validated['start_time']) || isset($validated['end_time']) || isset($validated['slot_interval'])) {
            $patternChanged = true;
        }
        if (isset($validated['valid_from']) || isset($validated['valid_to'])) {
            $patternChanged = true;
        }
        if (isset($validated['week_pattern']) && $validated['week_pattern'] !== $schedule->week_pattern) {
            $patternChanged = true;
        }
        if (isset($validated['specific_dates']) && $validated['specific_dates'] !== $schedule->specific_dates) {
            $patternChanged = true;
        }

        // Remove regenerate_slots from validated data before updating
        unset($validated['regenerate_slots']);

        $schedule->update($validated);

        // Regenerate slots if requested or if pattern changed
        if ($regenerateSlots || $patternChanged) {
            $slotsGenerated = $schedule->regenerateSlots();

            return redirect()->route('schedules.index')
                ->with('success', __('schedules.messages.updated_successfully', ['slots_generated' => $slotsGenerated]));
        }

        return redirect()->route('schedules.index')
            ->with('success', __('schedules.messages.updated_successfully', ['slots_generated' => 0]));
    }

    /**
     * Remove the specified schedule from storage.
     */
    public function destroy(Schedule $schedule): RedirectResponse
    {
        $this->authorize('delete', $schedule);

        // Delete all non-booked slots associated with this schedule
        Slot::where('schedule_id', $schedule->id)
            ->where('status', '!=', 'booked')
            ->delete();

        // Set schedule_id to null for booked slots (they should remain)
        Slot::where('schedule_id', $schedule->id)
            ->where('status', 'booked')
            ->update(['schedule_id' => null]);

        $schedule->delete();

        return redirect()->route('schedules.index')
            ->with('success', __('schedules.messages.deleted_successfully'));
    }
}
