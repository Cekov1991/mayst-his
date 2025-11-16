<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'name',
        'valid_from',
        'valid_to',
        'start_time',
        'end_time',
        'slot_interval',
        'days_of_week',
        'week_pattern',
        'specific_dates',
        'excluded_dates',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'valid_from' => 'date',
            'valid_to' => 'date',
            'days_of_week' => 'array',
            'week_pattern' => 'array',
            'specific_dates' => 'array',
            'excluded_dates' => 'array',
            'is_active' => 'boolean',
        ];
    }

    // Accessor for start_time to handle time field
    public function getStartTimeAttribute($value)
    {
        if (! $value) {
            return null;
        }

        if ($value instanceof Carbon) {
            return $value;
        }

        // Time field comes as string like "09:00:00" or "09:00"
        return Carbon::parse($value);
    }

    // Accessor for end_time to handle time field
    public function getEndTimeAttribute($value)
    {
        if (! $value) {
            return null;
        }

        if ($value instanceof Carbon) {
            return $value;
        }

        // Time field comes as string like "17:00:00" or "17:00"
        return Carbon::parse($value);
    }

    // Relationships
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function slots(): HasMany
    {
        return $this->hasMany(Slot::class);
    }

    // Generate all slots for this schedule
    public function generateSlots(): int
    {
        $generated = 0;
        $currentDate = $this->valid_from->copy();

        while ($currentDate->lte($this->valid_to)) {
            // Check if date matches schedule criteria
            if ($this->shouldGenerateForDate($currentDate)) {
                $generated += $this->generateSlotsForDate($currentDate);
            }

            $currentDate->addDay();
        }

        return $generated;
    }

    protected function shouldGenerateForDate(Carbon $date): bool
    {
        // Check excluded dates first
        if ($this->excluded_dates && in_array($date->format('Y-m-d'), $this->excluded_dates)) {
            return false;
        }

        // If specific_dates is set, only generate for those dates
        if ($this->specific_dates && ! empty($this->specific_dates)) {
            return in_array($date->format('Y-m-d'), $this->specific_dates);
        }

        // Check day of week
        $dayOfWeek = $date->dayOfWeek; // 0 = Sunday, 6 = Saturday
        if (! in_array($dayOfWeek, $this->days_of_week)) {
            return false;
        }

        // Check week pattern (for "every other Saturday")
        if ($this->week_pattern && ! empty($this->week_pattern)) {
            $weekOfMonth = $this->getWeekOfMonth($date);

            return in_array($weekOfMonth, $this->week_pattern);
        }

        return true;
    }

    protected function getWeekOfMonth(Carbon $date): int
    {
        $firstDayOfMonth = $date->copy()->startOfMonth();
        $weekNumber = (int) ceil(($date->day + $firstDayOfMonth->dayOfWeek) / 7);

        return $weekNumber;
    }

    protected function generateSlotsForDate(Carbon $date): int
    {
        $generated = 0;
        $startTimeStr = $this->attributes['start_time'] ?? $this->start_time->format('H:i:s');
        $endTimeStr = $this->attributes['end_time'] ?? $this->end_time->format('H:i:s');

        $slotStart = $date->copy()->setTimeFromTimeString($startTimeStr);
        $endTime = $date->copy()->setTimeFromTimeString($endTimeStr);


        while ($slotStart->copy()->addMinutes($this->slot_interval)->lte($endTime)) {
            $slotEnd = $slotStart->copy()->addMinutes($this->slot_interval);

            // Check if slot already exists
            $exists = Slot::where('doctor_id', $this->doctor_id)
                ->where('start_time', $slotStart)
                ->exists();

            if (! $exists) {
                Slot::create([
                    'doctor_id' => $this->doctor_id,
                    'schedule_id' => $this->id,
                    'start_time' => $slotStart->copy(),
                    'end_time' => $slotEnd,
                    'status' => 'available',
                ]);
                $generated++;
            }

            $slotStart->addMinutes($this->slot_interval);
        }

        return $generated;
    }

    // Remove slots for specific dates (when editing schedule)
    public function removeSlotsForDates(array $dates): int
    {
        return Slot::where('schedule_id', $this->id)
            ->whereIn('start_time', $dates)
            ->where('status', '!=', 'booked') // Don't remove booked slots
            ->delete();
    }

    // Regenerate slots (useful when schedule is updated)
    public function regenerateSlots(): int
    {
        // Remove all non-booked slots
        Slot::where('schedule_id', $this->id)
            ->where('status', '!=', 'booked')
            ->delete();

        // Regenerate
        return $this->generateSlots();
    }
}
