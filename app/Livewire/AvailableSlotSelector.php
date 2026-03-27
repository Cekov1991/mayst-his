<?php

namespace App\Livewire;

use App\Models\Slot;
use Livewire\Component;

class AvailableSlotSelector extends Component
{
    public $doctorId = null;

    public $selectedDate = null;

    public $selectedSlotId = null;

    public $selectedSlot = null;

    public $showSlots = false;

    public $label = null;

    protected $listeners = ['doctorChanged' => 'handleDoctorChange'];

    public function mount($doctorId = null, $selectedDate = null, $selectedSlotId = null, $label = null)
    {
        $this->doctorId = $doctorId;
        $this->selectedDate = $selectedDate ?: now()->format('Y-m-d');
        $this->selectedSlotId = $selectedSlotId;
        $this->label = $label ?: __('visits.scheduled_at');

        if ($this->selectedSlotId) {
            $this->selectedSlot = Slot::find($this->selectedSlotId);
            if ($this->selectedSlot) {
                $this->selectedDate = $this->selectedSlot->start_time->format('Y-m-d');
            }
        }
    }

    public function updatedSelectedDate()
    {
        $this->showSlots = false;
        $this->selectedSlotId = null;
        $this->selectedSlot = null;

        // If we have a doctor and date, automatically show slots
        if ($this->doctorId && $this->selectedDate) {
            $this->showSlots = true;
        }
    }

    public function handleDoctorChange($doctorId)
    {
        $this->doctorId = $doctorId;
        $this->showSlots = false;
        $this->selectedSlotId = null;
        $this->selectedSlot = null;
    }

    public function toggleSlots()
    {
        if (! $this->doctorId) {
            session()->flash('error', __('Please select a doctor first'));

            return;
        }

        if (! $this->selectedDate) {
            session()->flash('error', __('Please select a date first'));

            return;
        }

        $this->showSlots = ! $this->showSlots;
    }

    public function selectSlot($slotId)
    {
        $slot = Slot::find($slotId);
        if ($slot && $slot->isAvailable()) {
            $this->selectedSlotId = $slot->id;
            $this->selectedSlot = $slot;
            $this->showSlots = false;

            // Dispatch event to parent form with slot data
            $this->dispatch('slotSelected', [
                'slotId' => $slot->id,
                'scheduledAt' => $slot->start_time->format('Y-m-d\TH:i'),
            ]);
        }
    }

    public function clearSelection()
    {
        $this->selectedSlotId = null;
        $this->selectedSlot = null;
        $this->showSlots = false;

        $this->dispatch('slotSelected', [
            'slotId' => null,
            'scheduledAt' => null,
        ]);
    }

    public function getAvailableSlotsProperty()
    {
        if (! $this->doctorId || ! $this->selectedDate) {
            return collect();
        }

        return Slot::available()
            ->where('doctor_id', $this->doctorId)
            ->whereDate('start_time', $this->selectedDate)
            ->orderBy('start_time')
            ->get();
    }

    public function render()
    {
        return view('livewire.available-slot-selector');
    }
}
