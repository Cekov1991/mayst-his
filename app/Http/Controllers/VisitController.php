<?php

namespace App\Http\Controllers;

use App\Http\Requests\CopyVisitDataRequest;
use App\Http\Requests\StoreVisitRequest;
use App\Http\Requests\UpdateVisitRequest;
use App\Models\Patient;
use App\Models\Slot;
use App\Models\User;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class VisitController extends Controller
{
    /**
     * Display a listing of visits.
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Visit::class);

        $query = Visit::with(['patient', 'doctor'])
            ->visibleTo(Auth::user());

        // Handle search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('reason_for_visit', 'LIKE', "%{$search}%")
                    ->orWhere('room', 'LIKE', "%{$search}%")
                    ->orWhereHas('patient', function ($patientQuery) use ($search) {
                        $patientQuery->where('first_name', 'LIKE', "%{$search}%")
                            ->orWhere('last_name', 'LIKE', "%{$search}%");
                    });
            });
        }

        // Handle filter by status
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        // Handle filter by type
        if ($type = $request->get('type')) {
            $query->where('type', $type);
        }

        // Handle filter by doctor
        if ($doctorId = $request->get('doctor_id')) {
            $query->where('doctor_id', $doctorId);
        }

        // Handle date range filter
        if ($date = $request->get('date')) {
            $query->whereDate('scheduled_at', $date);
        }

        $visits = $query->orderBy('scheduled_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        // Get doctors for filter dropdown
        $doctors = User::role('doctor')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('visits.index', compact('visits', 'doctors'));
    }

    /**
     * Show the form for creating a new visit.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', Visit::class);

        $doctors = User::role('doctor')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $selectedPatientId = $request->get('patient_id');

        return view('visits.create', compact('doctors', 'selectedPatientId'));
    }

    /**
     * Store a newly created visit in storage.
     */
    public function store(StoreVisitRequest $request): RedirectResponse
    {
        $this->authorize('create', Visit::class);

        $validatedData = $request->validated();

        // Use DB transaction to ensure slot booking and visit creation are atomic
        DB::beginTransaction();

        try {
            // Get the slot and verify it's still available
            $slot = Slot::lockForUpdate()->find($validatedData['slot_id']);

            if (! $slot || ! $slot->isAvailable()) {
                throw new \Exception(__('The selected time slot is no longer available.'));
            }

            // Set scheduled_at from slot if not provided
            if (! $validatedData['scheduled_at']) {
                $validatedData['scheduled_at'] = $slot->start_time;
            }

            // Set timestamps based on status
            $this->setStatusTimestamps($validatedData);

            // Create the visit
            $visit = Visit::create($validatedData);

            // Mark slot as booked
            $slot->update(['status' => 'booked']);

            DB::commit();

            return redirect()->route('visits.show', $visit)
                ->with('success', __('visits.messages.created_successfully'));

        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified visit.
     */
    public function show(Visit $visit): View
    {
        $this->authorize('view', $visit);

        $visit->load([
            'patient',
            'doctor',
            'anamnesis',
            'ophthalmicExam.refractions',
            'imagingStudies.orderedBy',
            'imagingStudies.performedBy',
            'treatmentPlans',
            'prescriptions.doctor',
            'prescriptions.prescriptionItems',
            'spectaclePrescriptions.doctor',
        ]);

        $previousVisit = Visit::select('id')
            ->where('patient_id', $visit->patient_id)
            ->where('id', '<', $visit->id)
            ->orderBy('id', 'desc')
            ->first();

        return view('visits.show', compact('visit', 'previousVisit'));
    }

    /**
     * Show the form for editing the specified visit.
     */
    public function edit(Visit $visit): View
    {
        $this->authorize('update', $visit);

        $patients = Patient::orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        $doctors = User::role('doctor')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('visits.edit', compact('visit', 'patients', 'doctors'));
    }

    /**
     * Update the specified visit in storage.
     */
    public function update(UpdateVisitRequest $request, Visit $visit): RedirectResponse
    {
        $this->authorize('update', $visit);

        $validatedData = $request->validated();

        // Use DB transaction to handle slot changes atomically
        DB::beginTransaction();

        try {
            $oldSlotId = $visit->slot_id;
            $newSlotId = $validatedData['slot_id'] ?? null;

            // Handle slot changes
            if ($newSlotId && $oldSlotId !== $newSlotId) {
                // Get the new slot and verify it's available
                $newSlot = Slot::lockForUpdate()->find($newSlotId);

                if (! $newSlot || ! $newSlot->isAvailable()) {
                    throw new \Exception(__('The selected time slot is no longer available.'));
                }

                // Set scheduled_at from new slot if not provided
                if (! $validatedData['scheduled_at']) {
                    $validatedData['scheduled_at'] = $newSlot->start_time;
                }

                // Release old slot if it exists
                if ($oldSlotId) {
                    $oldSlot = Slot::find($oldSlotId);
                    if ($oldSlot && $oldSlot->isBooked()) {
                        $oldSlot->update(['status' => 'available']);
                    }
                }

                // Book new slot
                $newSlot->update(['status' => 'booked']);
            }

            // Handle status changes and timestamps
            $this->handleStatusChange($visit, $validatedData);

            // Update the visit
            $visit->update($validatedData);

            DB::commit();

            return redirect()->route('visits.show', $visit)
                ->with('success', __('visits.messages.updated_successfully'));

        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified visit from storage.
     */
    public function destroy(Visit $visit): RedirectResponse
    {
        $this->authorize('delete', $visit);

        DB::beginTransaction();

        try {
            // Release the associated slot
            if ($visit->slot_id) {
                $slot = Slot::find($visit->slot_id);
                if ($slot && $slot->isBooked()) {
                    $slot->update(['status' => 'available']);
                }
            }

            $visit->delete();

            DB::commit();

            return redirect()->route('visits.index')
                ->with('success', __('visits.messages.deleted_successfully'));
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('visits.index')
                ->with('error', __('visits.messages.delete_failed'));
        }
    }

    /**
     * Update visit status (for quick status changes).
     */
    public function updateStatus(Request $request, Visit $visit): RedirectResponse
    {
        $this->authorize('updateStatus', $visit);

        $request->validate([
            'status' => 'required|in:scheduled,arrived,in_progress,completed,cancelled',
        ]);

        $oldStatus = $visit->status;
        $newStatus = $request->status;

        // Set appropriate timestamps based on status transition
        $updateData = ['status' => $newStatus];
        $now = Carbon::now();

        switch ($newStatus) {
            case 'arrived':
                if ($oldStatus === 'scheduled') {
                    $updateData['arrived_at'] = $now;
                }
                break;
            case 'in_progress':
                if ($oldStatus === 'arrived') {
                    $updateData['started_at'] = $now;
                }
                break;
            case 'completed':
                if ($oldStatus === 'in_progress') {
                    $updateData['completed_at'] = $now;
                }
                break;
        }

        $visit->update($updateData);

        return redirect()->back()
            ->with('success', __('visits.messages.status_updated'));
    }

    /**
     * Set initial status timestamps for new visits.
     */
    private function setStatusTimestamps(array &$data): void
    {
        $now = Carbon::now();

        switch ($data['status']) {
            case 'arrived':
                $data['arrived_at'] = $now;
                break;
            case 'in_progress':
                $data['arrived_at'] = $now;
                $data['started_at'] = $now;
                break;
            case 'completed':
                $data['arrived_at'] = $now;
                $data['started_at'] = $now;
                $data['completed_at'] = $now;
                break;
        }
    }

    /**
     * Handle status changes and update appropriate timestamps.
     */
    private function handleStatusChange(Visit $visit, array &$data): void
    {
        $oldStatus = $visit->status;
        $newStatus = $data['status'];

        if ($oldStatus !== $newStatus) {
            $now = Carbon::now();

            switch ($newStatus) {
                case 'arrived':
                    if ($oldStatus === 'scheduled' && ! $visit->arrived_at) {
                        $data['arrived_at'] = $now;
                    }
                    break;
                case 'in_progress':
                    if (! $visit->arrived_at) {
                        $data['arrived_at'] = $now;
                    }
                    if ($oldStatus !== 'in_progress' && ! $visit->started_at) {
                        $data['started_at'] = $now;
                    }
                    break;
                case 'completed':
                    if (! $visit->arrived_at) {
                        $data['arrived_at'] = $now;
                    }
                    if (! $visit->started_at) {
                        $data['started_at'] = $now;
                    }
                    if ($oldStatus !== 'completed' && ! $visit->completed_at) {
                        $data['completed_at'] = $now;
                    }
                    break;
            }
        }
    }

    /**
     * Show the copy selection page.
     */
    public function showCopySelection(Visit $visit, Visit $previousVisit): View
    {
        // Authorization is handled in the CopyVisitDataRequest

        $previousVisit->load([
            'anamnesis',
            'ophthalmicExam.refractions',
            'treatmentPlans',
            'prescriptions.prescriptionItems',
            'spectaclePrescriptions',
            'diagnoses' => function ($query) {
                $query->whereIn('status', ['confirmed', 'working', 'provisional']);
            },
        ]);

        return view('visits.copy-selection', compact('visit', 'previousVisit'));
    }

    /**
     * Process the copy operation based on selected data.
     */
    public function processCopy(CopyVisitDataRequest $request, Visit $visit, Visit $previousVisit): RedirectResponse
    {
        $selectedData = $request->validated();
        $copiedItems = [];

        DB::transaction(function () use ($selectedData, $visit, $previousVisit, &$copiedItems) {
            // Copy medical history data
            if (isset($selectedData['medical_history']) && $previousVisit->anamnesis) {
                $this->copyMedicalHistory($visit, $previousVisit, $selectedData['medical_history'], $copiedItems);
            }

            // Copy diagnoses
            if (isset($selectedData['diagnoses'])) {
                $this->copyDiagnoses($visit, $previousVisit, $selectedData['diagnoses'], $copiedItems);
            }

            // Copy examination data
            if (isset($selectedData['examination_data']) && $previousVisit->ophthalmicExam) {
                $this->copyExaminationData($visit, $previousVisit, $selectedData['examination_data'], $copiedItems);
            }

            // Copy refractions
            if (isset($selectedData['refractions']) && $previousVisit->ophthalmicExam) {
                $this->copyRefractions($visit, $previousVisit, $selectedData['refractions'], $copiedItems);
            }

            // Copy prescriptions
            if (isset($selectedData['prescriptions'])) {
                $this->copyPrescriptions($visit, $previousVisit, $selectedData['prescriptions'], $copiedItems);
            }

            // Copy spectacle prescriptions
            if (isset($selectedData['spectacle_prescriptions'])) {
                $this->copySpectaclePrescriptions($visit, $previousVisit, $selectedData['spectacle_prescriptions'], $copiedItems);
            }

            // Copy treatment plans
            if (isset($selectedData['treatment_plans'])) {
                $this->copyTreatmentPlans($visit, $previousVisit, $selectedData['treatment_plans'], $copiedItems);
            }
        });

        $copiedItemsText = implode(', ', $copiedItems);

        return redirect()->route('visits.show', $visit)
            ->with('success', __('visits.messages.copied_successfully', ['items' => $copiedItemsText]));
    }

    /**
     * Copy selected medical history fields.
     */
    private function copyMedicalHistory(Visit $visit, Visit $previousVisit, array $fields, array &$copiedItems): void
    {
        $anamnesisData = $previousVisit->anamnesis->only($fields);
        $anamnesisData['visit_id'] = $visit->id;

        $visit->anamnesis()->updateOrCreate(
            ['visit_id' => $visit->id],
            $anamnesisData
        );

        $copiedItems[] = __('visits.medical_history');
    }

    /**
     * Copy selected diagnoses.
     */
    private function copyDiagnoses(Visit $visit, Visit $previousVisit, array $diagnosisIds, array &$copiedItems): void
    {
        $selectedDiagnoses = $previousVisit->diagnoses->whereIn('id', $diagnosisIds);

        foreach ($selectedDiagnoses as $diagnosis) {
            $visit->diagnoses()->create([
                'patient_id' => $diagnosis->patient_id,
                'diagnosed_by' => Auth::id(),
                'is_primary' => $diagnosis->is_primary,
                'eye' => $diagnosis->eye,
                'code' => $diagnosis->code,
                'code_system' => $diagnosis->code_system,
                'term' => $diagnosis->term,
                'status' => $diagnosis->status,
                'onset_date' => $diagnosis->onset_date,
                'severity' => $diagnosis->severity,
                'acuity' => $diagnosis->acuity,
                'notes' => ($diagnosis->notes ?? '').' (Copied from previous visit)',
            ]);
        }

        $copiedItems[] = __('visits.diagnoses').' ('.count($selectedDiagnoses).')';
    }

    /**
     * Copy selected examination data.
     */
    private function copyExaminationData(Visit $visit, Visit $previousVisit, array $fields, array &$copiedItems): void
    {
        $examData = $previousVisit->ophthalmicExam->only($fields);
        $examData['visit_id'] = $visit->id;

        $visit->ophthalmicExam()->updateOrCreate(
            ['visit_id' => $visit->id],
            $examData
        );

        $copiedItems[] = __('visits.examination_data');
    }

    /**
     * Copy selected refractions.
     */
    private function copyRefractions(Visit $visit, Visit $previousVisit, array $refractionIds, array &$copiedItems): void
    {
        $selectedRefractions = $previousVisit->ophthalmicExam->refractions->whereIn('id', $refractionIds);

        // Ensure ophthalmic exam exists
        $exam = $visit->ophthalmicExam()->firstOrCreate(['visit_id' => $visit->id]);

        foreach ($selectedRefractions as $refraction) {
            $refractionData = $refraction->only([
                'eye', 'method', 'sphere', 'cylinder', 'axis', 'add_power', 'prism', 'base',
            ]);
            $refractionData['ophthalmic_exam_id'] = $exam->id;
            $refractionData['notes'] = 'Baseline from previous visit: '.($refraction->notes ?? '');

            $exam->refractions()->create($refractionData);
        }

        $copiedItems[] = __('visits.refractions').' ('.count($selectedRefractions).')';
    }

    /**
     * Copy selected prescriptions.
     */
    private function copyPrescriptions(Visit $visit, Visit $previousVisit, array $prescriptionIds, array &$copiedItems): void
    {
        $selectedPrescriptions = $previousVisit->prescriptions->whereIn('id', $prescriptionIds);

        foreach ($selectedPrescriptions as $prescription) {
            $newPrescription = $visit->prescriptions()->create([
                'doctor_id' => Auth::id(),
                'notes' => ($prescription->notes ?? '').' (Copied from previous visit)',
            ]);

            // Copy prescription items
            foreach ($prescription->prescriptionItems as $item) {
                $newPrescription->prescriptionItems()->create([
                    'drug_name' => $item->drug_name,
                    'form' => $item->form,
                    'strength' => $item->strength,
                    'dosage_instructions' => $item->dosage_instructions,
                    'duration_days' => $item->duration_days,
                    'repeats' => $item->repeats,
                ]);
            }
        }

        $copiedItems[] = __('visits.prescriptions').' ('.count($selectedPrescriptions).')';
    }

    /**
     * Copy selected spectacle prescriptions.
     */
    private function copySpectaclePrescriptions(Visit $visit, Visit $previousVisit, array $spectacleIds, array &$copiedItems): void
    {
        $selectedSpectacles = $previousVisit->spectaclePrescriptions->whereIn('id', $spectacleIds);

        foreach ($selectedSpectacles as $spectacle) {
            $spectacleData = $spectacle->only([
                'od_sphere', 'od_cylinder', 'od_axis', 'od_add',
                'os_sphere', 'os_cylinder', 'os_axis', 'os_add',
                'pd_distance', 'pd_near', 'type',
            ]);
            $spectacleData['visit_id'] = $visit->id;
            $spectacleData['doctor_id'] = Auth::id();
            $spectacleData['notes'] = ($spectacle->notes ?? '').' (Copied from previous visit)';
            $spectacleData['valid_until'] = $spectacle->valid_until;

            $visit->spectaclePrescriptions()->create($spectacleData);
        }

        $copiedItems[] = __('visits.spectacle_prescriptions').' ('.count($selectedSpectacles).')';
    }

    /**
     * Copy selected treatment plans.
     */
    private function copyTreatmentPlans(Visit $visit, Visit $previousVisit, array $planIds, array &$copiedItems): void
    {
        $selectedPlans = $previousVisit->treatmentPlans->whereIn('id', $planIds);

        foreach ($selectedPlans as $plan) {
            $visit->treatmentPlans()->create([
                'plan_type' => $plan->plan_type,
                'details' => ($plan->details ?? '').' (Continued from previous visit)',
                'planned_date' => $plan->planned_date,
            ]);
        }

        $copiedItems[] = __('visits.treatment_plans').' ('.count($selectedPlans).')';
    }
}
