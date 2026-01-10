<?php

namespace App\Livewire\Schedules;

use App\Models\Schedule;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ScheduleForm extends Component
{
    public ?Schedule $schedule = null;

    public ?array $doctors = null;

    public ?int $doctorId = null;

    public ?string $name = null;

    public string $validFrom = '';

    public string $validTo = '';

    public string $startTime = '09:00';

    public string $endTime = '17:00';

    public int $slotInterval = 30;

    public array $daysOfWeek = [1, 2, 3, 4, 5]; // Default to Monday-Friday

    public array $weekPattern = [];

    public array $specificDates = [];

    public array $excludedDates = [];

    public bool $isActive = true;

    public bool $regenerateSlots = false;

    public function mount(?Schedule $schedule = null): void
    {
        $this->schedule = $schedule;

        if ($schedule->exists) {
            $this->authorize('update', $schedule);
            $this->schedule = $schedule;
            $this->doctorId = $schedule->doctor_id;
            $this->name = $schedule->name;
            $this->validFrom = $schedule->valid_from->format('Y-m-d');
            $this->validTo = $schedule->valid_to->format('Y-m-d');
            $this->startTime = $schedule->start_time->format('H:i');
            $this->endTime = $schedule->end_time->format('H:i');
            $this->slotInterval = $schedule->slot_interval;
            $this->daysOfWeek = $schedule->days_of_week ?? [1, 2, 3, 4, 5];
            $this->weekPattern = $schedule->week_pattern ?? [];
            $this->specificDates = $schedule->specific_dates ?? [];
            $this->excludedDates = $schedule->excluded_dates ?? [];
            $this->isActive = $schedule->is_active ?? true;
        } else {
            $this->authorize('create', Schedule::class);
            $this->validFrom = today()->format('Y-m-d');
            $this->validTo = today()->addMonth()->endOfMonth()->format('Y-m-d');
            if (Auth::user()->hasRole('doctor')) {
                $this->doctorId = Auth::id();
            }
        }

        // Load doctors if user has permission
        if (Auth::user()->hasRole(['admin', 'receptionist'])) {
            $this->doctors = User::role('doctor')
                ->where('is_active', true)
                ->orderBy('name')
                ->get()
                ->map(fn ($doctor) => ['id' => $doctor->id, 'name' => $doctor->name])
                ->values()
                ->toArray();
        }
    }

    public function addSpecificDate(): void
    {
        $this->specificDates[] = '';
    }

    public function removeSpecificDate(int $index): void
    {
        unset($this->specificDates[$index]);
        $this->specificDates = array_values($this->specificDates); // Reindex array
    }

    public function addExcludedDate(): void
    {
        $this->excludedDates[] = '';
    }

    public function removeExcludedDate(int $index): void
    {
        unset($this->excludedDates[$index]);
        $this->excludedDates = array_values($this->excludedDates); // Reindex array
    }

    public function toggleDayOfWeek(int $day): void
    {
        if (in_array($day, $this->daysOfWeek)) {
            $this->daysOfWeek = array_values(array_filter($this->daysOfWeek, fn ($d) => $d !== $day));
        } else {
            $this->daysOfWeek[] = $day;
            sort($this->daysOfWeek);
        }
    }

    public function toggleWeekPattern(int $week): void
    {
        if (in_array($week, $this->weekPattern)) {
            $this->weekPattern = array_values(array_filter($this->weekPattern, fn ($w) => $w !== $week));
        } else {
            $this->weekPattern[] = $week;
            sort($this->weekPattern);
        }
    }

    public function save(): void
    {
        if ($this->schedule->exists) {
            $this->authorize('update', $this->schedule);
        } else {
            $this->authorize('create', Schedule::class);
        }

        $rules = $this->schedule
            ? [
                'doctorId' => 'sometimes|required|exists:users,id',
                'name' => 'nullable|string|max:255',
                'validFrom' => 'sometimes|required|date',
                'validTo' => 'sometimes|required|date|after_or_equal:validFrom',
                'startTime' => 'sometimes|required|date_format:H:i',
                'endTime' => 'sometimes|required|date_format:H:i|after:startTime',
                'slotInterval' => 'sometimes|required|integer|in:15,30,45,60',
                'daysOfWeek' => 'sometimes|required|array|min:1',
                'daysOfWeek.*' => 'integer|min:0|max:6',
                'weekPattern' => 'nullable|array',
                'weekPattern.*' => 'integer|min:1|max:5',
                'specificDates' => 'nullable|array',
                'specificDates.*' => 'date',
                'excludedDates' => 'nullable|array',
                'excludedDates.*' => 'date',
                'isActive' => 'sometimes|boolean',
                'regenerateSlots' => 'sometimes|boolean',
            ]
            : [
                'doctorId' => 'required|exists:users,id',
                'name' => 'nullable|string|max:255',
                'validFrom' => 'required|date',
                'validTo' => 'required|date|after_or_equal:validFrom',
                'startTime' => 'required|date_format:H:i',
                'endTime' => 'required|date_format:H:i|after:startTime',
                'slotInterval' => 'required|integer|in:15,30,45,60',
                'daysOfWeek' => 'required|array|min:1',
                'daysOfWeek.*' => 'integer|min:0|max:6',
                'weekPattern' => 'nullable|array',
                'weekPattern.*' => 'integer|min:1|max:5',
                'specificDates' => 'nullable|array',
                'specificDates.*' => 'date',
                'excludedDates' => 'nullable|array',
                'excludedDates.*' => 'date',
                'isActive' => 'sometimes|boolean',
            ];

        $this->validate($rules);

        // Filter out empty dates
        $this->specificDates = array_filter($this->specificDates, fn ($date) => ! empty($date));
        $this->excludedDates = array_filter($this->excludedDates, fn ($date) => ! empty($date));

        if ($this->schedule->exists) {
            $this->update();
        } else {
            $this->store();
        }
    }

    protected function store(): void
    {
        $schedule = Schedule::create([
            'doctor_id' => $this->doctorId,
            'name' => $this->name ?: null,
            'valid_from' => $this->validFrom,
            'valid_to' => $this->validTo,
            'start_time' => $this->startTime,
            'end_time' => $this->endTime,
            'slot_interval' => $this->slotInterval,
            'days_of_week' => $this->daysOfWeek,
            'week_pattern' => ! empty($this->weekPattern) ? $this->weekPattern : null,
            'specific_dates' => ! empty($this->specificDates) ? array_values($this->specificDates) : null,
            'excluded_dates' => ! empty($this->excludedDates) ? array_values($this->excludedDates) : null,
            'is_active' => $this->isActive,
        ]);

        // Generate slots for the schedule
        $slotsGenerated = $schedule->generateSlots();

        session()->flash('success', __('schedules.messages.created_successfully', ['slots_generated' => $slotsGenerated]));

        $this->redirect(route('schedules.index'), navigate: true);
    }

    protected function update(): void
    {
        $patternChanged = false;

        // Check if schedule pattern changed
        if ($this->daysOfWeek !== $this->schedule->days_of_week) {
            $patternChanged = true;
        }
        if ($this->startTime !== $this->schedule->start_time->format('H:i') ||
            $this->endTime !== $this->schedule->end_time->format('H:i') ||
            $this->slotInterval !== $this->schedule->slot_interval) {
            $patternChanged = true;
        }
        if ($this->validFrom !== $this->schedule->valid_from->format('Y-m-d') ||
            $this->validTo !== $this->schedule->valid_to->format('Y-m-d')) {
            $patternChanged = true;
        }
        if ($this->weekPattern !== ($this->schedule->week_pattern ?? [])) {
            $patternChanged = true;
        }
        if ($this->specificDates !== ($this->schedule->specific_dates ?? [])) {
            $patternChanged = true;
        }

        $this->schedule->update([
            'doctor_id' => $this->doctorId,
            'name' => $this->name ?: null,
            'valid_from' => $this->validFrom,
            'valid_to' => $this->validTo,
            'start_time' => $this->startTime,
            'end_time' => $this->endTime,
            'slot_interval' => $this->slotInterval,
            'days_of_week' => $this->daysOfWeek,
            'week_pattern' => ! empty($this->weekPattern) ? $this->weekPattern : null,
            'specific_dates' => ! empty($this->specificDates) ? array_values($this->specificDates) : null,
            'excluded_dates' => ! empty($this->excludedDates) ? array_values($this->excludedDates) : null,
            'is_active' => $this->isActive,
        ]);

        // Regenerate slots if requested or if pattern changed
        if ($this->regenerateSlots || $patternChanged) {
            $slotsGenerated = $this->schedule->regenerateSlots();

            session()->flash('success', __('schedules.messages.updated_successfully', ['slots_generated' => $slotsGenerated]));
        } else {
            session()->flash('success', __('schedules.messages.updated_successfully', ['slots_generated' => 0]));
        }

        $this->redirect(route('schedules.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.schedules.schedule-form');
    }
}
