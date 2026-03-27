<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
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
}
