<?php

namespace App\Livewire\Visits;

use App\Models\Prescription;
use App\Models\Visit;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PrescriptionForm extends Component
{
    public Visit $visit;

    public ?Prescription $prescription = null;

    public string $notes = '';

    public array $items = [];

    public function mount(Visit $visit, ?Prescription $prescription): void
    {
        $this->authorize('accessMedicalWorkspace', $visit);

        $this->visit = $visit;
        $this->prescription = $prescription;

        if ($prescription->exists) {
            $this->notes = $prescription->notes ?? '';
            $this->items = $prescription->prescriptionItems->map(function ($item) {
                return [
                    'drug_name' => $item->drug_name,
                    'form' => $item->form,
                    'strength' => $item->strength ?? '',
                    'dosage_instructions' => $item->dosage_instructions,
                    'duration_days' => $item->duration_days ?? '',
                    'repeats' => $item->repeats ?? '',
                ];
            })->toArray();
        } else {
            // Initialize with one empty item for create mode
            $this->items = [
                [
                    'drug_name' => '',
                    'form' => 'drops',
                    'strength' => '',
                    'dosage_instructions' => '',
                    'duration_days' => '',
                    'repeats' => '',
                ],
            ];
        }
    }

    public function addMedicationRow(): void
    {
        $this->items[] = [
            'drug_name' => '',
            'form' => 'drops',
            'strength' => '',
            'dosage_instructions' => '',
            'duration_days' => '',
            'repeats' => '',
        ];
    }

    public function removeMedicationRow(int $index): void
    {
        if (count($this->items) > 1) {
            unset($this->items[$index]);
            $this->items = array_values($this->items); // Reindex array
        }
    }

    public function getCanRemoveItemProperty(): bool
    {
        return count($this->items) > 1;
    }

    public function save(): void
    {
        $this->authorize('accessMedicalWorkspace', $this->visit);

        $this->validate([
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.drug_name' => 'required|string|max:255',
            'items.*.form' => 'required|in:drops,ointment,tablet,capsule,other',
            'items.*.strength' => 'nullable|string|max:50',
            'items.*.dosage_instructions' => 'required|string|max:255',
            'items.*.duration_days' => 'nullable|integer|min:1',
            'items.*.repeats' => 'nullable|integer|min:0',
        ]);

        if ($this->prescription->exists) {
            $this->update();
        } else {
            $this->store();
        }
    }

    protected function store(): void
    {
        $prescription = $this->visit->prescriptions()->create([
            'doctor_id' => Auth::id(),
            'notes' => $this->notes ?: null,
        ]);

        foreach ($this->items as $item) {
            $prescription->prescriptionItems()->create([
                'drug_name' => $item['drug_name'],
                'form' => $item['form'],
                'strength' => $item['strength'] ?: null,
                'dosage_instructions' => $item['dosage_instructions'],
                'duration_days' => $item['duration_days'] ? (int) $item['duration_days'] : null,
                'repeats' => $item['repeats'] ? (int) $item['repeats'] : null,
            ]);
        }

        session()->flash('success', __('common.messages.saved_successfully'));

        $this->redirect(route('visits.prescriptions', $this->visit), navigate: true);
    }

    protected function update(): void
    {
        $this->prescription->update([
            'notes' => $this->notes ?: null,
        ]);

        // Delete existing items and recreate them
        $this->prescription->prescriptionItems()->delete();

        foreach ($this->items as $item) {
            $this->prescription->prescriptionItems()->create([
                'drug_name' => $item['drug_name'],
                'form' => $item['form'],
                'strength' => $item['strength'] ?: null,
                'dosage_instructions' => $item['dosage_instructions'],
                'duration_days' => $item['duration_days'] ? (int) $item['duration_days'] : null,
                'repeats' => $item['repeats'] ? (int) $item['repeats'] : null,
            ]);
        }

        session()->flash('success', __('common.messages.saved_successfully'));

        $this->redirect(route('visits.prescriptions', $this->visit), navigate: true);
    }

    public function render()
    {
        return view('livewire.visits.prescription-form');
    }
}
