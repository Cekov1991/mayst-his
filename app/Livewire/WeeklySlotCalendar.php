<?php

namespace App\Livewire;

use App\Models\Slot;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class WeeklySlotCalendar extends Component
{
    use AuthorizesRequests;

    public $weekStartDate;

    public $doctorId = null;

    public function mount($doctorId = null)
    {
        $this->doctorId = $doctorId;
        $this->weekStartDate = now()->startOfWeek()->format('Y-m-d');
    }

    public function previousWeek()
    {
        $this->weekStartDate = Carbon::parse($this->weekStartDate)->subWeek()->format('Y-m-d');
    }

    public function nextWeek()
    {
        $this->weekStartDate = Carbon::parse($this->weekStartDate)->addWeek()->format('Y-m-d');
    }

    public function goToToday()
    {
        $this->weekStartDate = now()->startOfWeek()->format('Y-m-d');
    }

    public function getWeekDatesProperty(): Collection
    {
        $start = Carbon::parse($this->weekStartDate);

        return collect(range(0, 6))->map(fn ($i) => $start->copy()->addDays($i));
    }

    public function getSlotsProperty(): Collection
    {
        $weekStart = Carbon::parse($this->weekStartDate);
        $weekEnd = $weekStart->copy()->endOfWeek();

        $query = Slot::with(['doctor', 'visit.patient'])
            ->whereBetween('start_time', [$weekStart, $weekEnd]);

        if ($this->doctorId) {
            $query->where('doctor_id', $this->doctorId);
        } elseif (Auth::user()->hasRole('doctor')) {
            $query->where('doctor_id', Auth::id());
        }

        $slots = $query->get()->groupBy([
            fn ($slot) => $slot->start_time->format('Y-m-d'),
            fn ($slot) => $slot->start_time->format('H:i'),
        ]);

        return $slots;
    }

    public function getSlotsByDateProperty(): Collection
    {
        return $this->slots->groupBy(fn ($slot) => $slot->start_time->format('Y-m-d'));
    }

    public function getDoctorsProperty(): Collection
    {
        if (Auth::user()->hasRole(['admin', 'receptionist'])) {
            return User::role('doctor')->where('is_active', true)->orderBy('name')->get();
        }

        return collect();
    }

    public $selectedSlot = null;

    public $showSlotModal = false;

    protected $listeners = ['deleteSlotConfirmed' => 'deleteSlot', 'blockSlotConfirmed' => 'blockSlot', 'unblockSlotConfirmed' => 'unblockSlot'];

    public function handleSlotClick($slotId)
    {
        $this->selectedSlot = Slot::with(['doctor', 'visit.patient'])->find($slotId);
        $this->showSlotModal = true;
    }

    public function blockSlot($slotId, $applyToAll = false)
    {
        $slot = Slot::findOrFail($slotId);
        $this->authorize('update', $slot);

        if ($applyToAll) {
            $dayOfWeek = $slot->start_time->dayOfWeek;
            $mysqlDayOfWeek = $dayOfWeek === 0 ? 1 : $dayOfWeek + 1;
            $time = $slot->start_time->format('H:i:s');

            Slot::where('doctor_id', $slot->doctor_id)
                ->whereRaw('DAYOFWEEK(start_time) = ?', [$mysqlDayOfWeek])
                ->whereTime('start_time', $time)
                ->where('start_time', '>=', $slot->start_time)
                ->where('status', '!=', 'booked')
                ->update(['status' => 'blocked']);
        } else {
            if ($slot->isBooked()) {
                session()->flash('error', __('slots.messages.cannot_update_booked'));

                return;
            }
            $slot->update(['status' => 'blocked']);
        }

        $this->selectedSlot = null;
        $this->showSlotModal = false;
        session()->flash('success', __('slots.messages.status_updated', ['count' => 1]));
    }

    public function unblockSlot($slotId, $applyToAll = false)
    {
        $slot = Slot::findOrFail($slotId);
        $this->authorize('update', $slot);

        if ($applyToAll) {
            $dayOfWeek = $slot->start_time->dayOfWeek;
            $mysqlDayOfWeek = $dayOfWeek === 0 ? 1 : $dayOfWeek + 1;
            $time = $slot->start_time->format('H:i:s');

            Slot::where('doctor_id', $slot->doctor_id)
                ->whereRaw('DAYOFWEEK(start_time) = ?', [$mysqlDayOfWeek])
                ->whereTime('start_time', $time)
                ->where('start_time', '>=', $slot->start_time)
                ->where('status', '!=', 'booked')
                ->update(['status' => 'available']);
        } else {
            if ($slot->isBooked()) {
                session()->flash('error', __('slots.messages.cannot_update_booked'));

                return;
            }
            $slot->update(['status' => 'available']);
        }

        $this->selectedSlot = null;
        $this->showSlotModal = false;
        session()->flash('success', __('slots.messages.status_updated', ['count' => 1]));
    }

    public function confirmDeleteSlot($slotId)
    {
        $this->dispatch('openConfirmationModal',
            title: __('slots.confirm_delete'),
            content: __('slots.confirm_delete'),
            confirmEvent: 'deleteSlotConfirmed',
            confirmParams: [$slotId],
            confirmText: __('common.delete'),
            cancelText: __('common.cancel')
        );
    }

    public function confirmBlockSlot($slotId)
    {
        $this->dispatch('openConfirmationModal',
            title: __('slots.confirm_block'),
            content: __('slots.confirm_block'),
            confirmEvent: 'blockSlotConfirmed',
            confirmParams: [$slotId],
        );
    }

    public function confirmUnblockSlot($slotId)
    {
        $this->dispatch('openConfirmationModal',
            title: __('slots.confirm_unblock'),
            content: __('slots.confirm_unblock'),
            confirmEvent: 'unblockSlotConfirmed',
            confirmParams: [$slotId],
        );
    }

    public function deleteSlot($slotId, $applyToAll = false)
    {
        $slot = Slot::findOrFail($slotId);
        $this->authorize('delete', $slot);

        if ($applyToAll) {
            $dayOfWeek = $slot->start_time->dayOfWeek;
            $mysqlDayOfWeek = $dayOfWeek === 0 ? 1 : $dayOfWeek + 1;
            $time = $slot->start_time->format('H:i:s');

            Slot::where('doctor_id', $slot->doctor_id)
                ->whereRaw('DAYOFWEEK(start_time) = ?', [$mysqlDayOfWeek])
                ->whereTime('start_time', $time)
                ->where('start_time', '>=', $slot->start_time)
                ->whereIn('status', ['available', 'blocked'])
                ->delete();
        } else {
            if ($slot->isBooked() && $slot->visit) {
                session()->flash('error', __('slots.messages.cannot_delete_booked'));

                return;
            }
            $slot->delete();
        }

        $this->selectedSlot = null;
        $this->showSlotModal = false;
        session()->flash('success', __('slots.messages.deleted_successfully', ['count' => 1]));
    }

    public function closeModal()
    {
        $this->selectedSlot = null;
        $this->showSlotModal = false;
    }

    public function render()
    {
        return view('livewire.weekly-slot-calendar');
    }
}
