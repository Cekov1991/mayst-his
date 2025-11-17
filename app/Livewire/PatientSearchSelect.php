<?php

namespace App\Livewire;

use App\Models\Patient;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PatientSearchSelect extends Component
{
    public $search = '';

    public $selectedPatientId = null;

    public $selectedPatient = null;

    public $label = null;

    public $showResults = false;

    public function mount($value = null, $label = null)
    {
        $this->selectedPatientId = $value;
        $this->label = $label;
        if ($value) {
            $this->selectedPatient = Patient::find($value);
            if ($this->selectedPatient) {
                $this->search = $this->selectedPatient->full_name.' - '.$this->selectedPatient->dob->format('M d, Y');
            }
        }
    }

    public function updatedSearch()
    {
        // Reset selection if user starts typing something different
        if ($this->selectedPatientId && $this->selectedPatient) {
            $expectedSearch = $this->selectedPatient->full_name.' - '.$this->selectedPatient->dob->format('M d, Y');
            if ($this->search !== $expectedSearch) {
                $this->selectedPatientId = null;
                $this->selectedPatient = null;
            }
        }
    }

    public function selectPatient($patientId)
    {
        $patient = Patient::find($patientId);
        if ($patient) {
            $this->selectedPatientId = $patient->id;
            $this->selectedPatient = $patient;
            $this->search = $patient->full_name.' - '.$patient->dob->format('M d, Y');
        }
    }

    public function clearSelection()
    {
        $this->selectedPatientId = null;
        $this->selectedPatient = null;
        $this->search = '';
    }

    public function getPatientsProperty()
    {
        if (strlen($this->search) < 2) {
            return collect();
        }

        return Patient::visibleTo(Auth::user())
            ->where(function ($q) {
                $q->where('first_name', 'LIKE', "%{$this->search}%")
                    ->orWhere('last_name', 'LIKE', "%{$this->search}%")
                    ->orWhere('unique_master_citizen_number', 'LIKE', "%{$this->search}%");
            })
            ->limit(10)
            ->get();
    }

    public function render()
    {
        return view('livewire.patient-search-select');
    }
}
