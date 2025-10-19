<?php

namespace App\Livewire;

use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;

class DoctorQueue extends Component
{
    public $selectedDate;

    public function mount()
    {
        $this->selectedDate = today()->format('Y-m-d');
    }

    public function render()
    {
        $visits = $this->getQueuedVisits();

        return view('livewire.doctor-queue', [
            'visits' => $visits,
            'stats' => $this->getStats($visits),
        ]);
    }

    private function getQueuedVisits()
    {
        return Visit::with(['patient'])
            ->where('doctor_id', Auth::id())
            ->whereDate('scheduled_at', $this->selectedDate)
            ->queue()
            ->get();
    }

    private function getStats($visits)
    {
        return [
            'total' => $visits->count(),
            'arrived' => $visits->where('status', 'arrived')->count(),
            'in_progress' => $visits->where('status', 'in_progress')->count(),
        ];
    }

    public function startVisit($visitId)
    {
        $visit = Visit::find($visitId);

        if (!$visit || $visit->doctor_id !== Auth::id() || $visit->status !== 'arrived') {
            $this->dispatch('show-message', [
                'type' => 'error',
                'message' => __('messages.unauthorized')
            ]);
            return;
        }

        $visit->update([
            'status' => 'in_progress',
            'started_at' => Carbon::now(),
        ]);

        $this->dispatch('show-message', [
            'type' => 'success',
            'message' => __('visits.messages.status_updated')
        ]);

        // Refresh the component
        $this->dispatch('visit-updated');
    }

    public function completeVisit($visitId)
    {
        $visit = Visit::find($visitId);

        if (!$visit || $visit->doctor_id !== Auth::id() || $visit->status !== 'in_progress') {
            $this->dispatch('show-message', [
                'type' => 'error',
                'message' => __('messages.unauthorized')
            ]);
            return;
        }

        $visit->update([
            'status' => 'completed',
            'completed_at' => Carbon::now(),
        ]);

        $this->dispatch('show-message', [
            'type' => 'success',
            'message' => __('visits.messages.status_updated')
        ]);

        // Refresh the component
        $this->dispatch('visit-updated');
    }

    public function updatedSelectedDate()
    {
        // Component will re-render when selectedDate changes
    }

    #[On('visit-updated')]
    public function refreshComponent()
    {
        // This method will be called when visit-updated event is dispatched
        // Component automatically re-renders
    }

    public function refreshQueue()
    {
        // Manual refresh method
        $this->dispatch('visit-updated');
    }

    public function getEmptyMessageProperty()
    {
        if ($this->selectedDate === today()->format('Y-m-d')) {
            return 'No patients have arrived yet for today.';
        }

        $formattedDate = \Carbon\Carbon::parse($this->selectedDate)->format('M d, Y');
        return "No visits found for {$formattedDate}.";
    }
}
