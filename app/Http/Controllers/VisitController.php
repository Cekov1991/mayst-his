<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVisitRequest;
use App\Http\Requests\UpdateVisitRequest;
use App\Models\Visit;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Anamnesis;
use App\Models\OphthalmicExam;
use App\Models\Refraction;
use App\Models\ImagingStudy;
use App\Models\TreatmentPlan;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\SpectaclePrescription;
use App\Http\Requests\StoreAnamnesisRequest;
use App\Http\Requests\StoreExamRequest;
use App\Http\Requests\StoreRefractionRequest;
use App\Http\Requests\StorePrescriptionWorkspaceRequest;

class VisitController extends Controller
{
    /**
     * Display a listing of visits.
     */
    public function index(Request $request): View
    {
        $query = Visit::with(['patient', 'doctor']);

        // Handle search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('reason_for_visit', 'LIKE', "%{$search}%")
                  ->orWhere('room', 'LIKE', "%{$search}%")
                  ->orWhereHas('patient', function ($patientQuery) use ($search) {
                      $patientQuery->where('first_name', 'LIKE', "%{$search}%")
                                   ->orWhere('last_name', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('doctor', function ($doctorQuery) use ($search) {
                      $doctorQuery->where('name', 'LIKE', "%{$search}%");
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
        $doctors = User::where('role', 'doctor')
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
        $patients = Patient::orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        $doctors = User::where('role', 'doctor')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $selectedPatientId = $request->get('patient_id');

        return view('visits.create', compact('patients', 'doctors', 'selectedPatientId'));
    }

    /**
     * Store a newly created visit in storage.
     */
    public function store(StoreVisitRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        // Set timestamps based on status
        $this->setStatusTimestamps($validatedData);

        $visit = Visit::create($validatedData);

        return redirect()->route('visits.show', $visit)
            ->with('success', his_trans('visits.messages.created_successfully'));
    }

    /**
     * Display the specified visit.
     */
    public function show(Visit $visit): View
    {
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
            'spectaclePrescriptions.doctor'
        ]);

        return view('visits.show', compact('visit'));
    }

    /**
     * Show the form for editing the specified visit.
     */
    public function edit(Visit $visit): View
    {
        $patients = Patient::orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        $doctors = User::where('role', 'doctor')
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
        $validatedData = $request->validated();

        // Handle status changes and timestamps
        $this->handleStatusChange($visit, $validatedData);

        $visit->update($validatedData);

        return redirect()->route('visits.show', $visit)
            ->with('success', his_trans('visits.messages.updated_successfully'));
    }

    /**
     * Remove the specified visit from storage.
     */
    public function destroy(Visit $visit): RedirectResponse
    {
        try {
            $visit->delete();

            return redirect()->route('visits.index')
                ->with('success', his_trans('visits.messages.deleted_successfully'));
        } catch (\Exception $e) {
            return redirect()->route('visits.index')
                ->with('error', his_trans('visits.messages.delete_failed'));
        }
    }

    /**
     * Update visit status (for quick status changes).
     */
    public function updateStatus(Request $request, Visit $visit): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:scheduled,arrived,in_progress,completed,cancelled'
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
            ->with('success', his_trans('visits.messages.status_updated'));
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
                    if ($oldStatus === 'scheduled' && !$visit->arrived_at) {
                        $data['arrived_at'] = $now;
                    }
                    break;
                case 'in_progress':
                    if (!$visit->arrived_at) {
                        $data['arrived_at'] = $now;
                    }
                    if ($oldStatus !== 'in_progress' && !$visit->started_at) {
                        $data['started_at'] = $now;
                    }
                    break;
                case 'completed':
                    if (!$visit->arrived_at) {
                        $data['arrived_at'] = $now;
                    }
                    if (!$visit->started_at) {
                        $data['started_at'] = $now;
                    }
                    if ($oldStatus !== 'completed' && !$visit->completed_at) {
                        $data['completed_at'] = $now;
                    }
                    break;
            }
        }
    }

    // ===== WORKSPACE PAGE METHODS =====

    /**
     * Show anamnesis page for a visit.
     */
    public function showAnamnesis(Visit $visit): View
    {
        $visit->load(['patient', 'doctor', 'anamnesis']);
        return view('visits.workspace.anamnesis', compact('visit'));
    }

    /**
     * Show examination page for a visit.
     */
    public function showExamination(Visit $visit): View
    {
        $visit->load(['patient', 'doctor', 'ophthalmicExam.refractions']);
        return view('visits.workspace.examination', compact('visit'));
    }

    /**
     * Show imaging page for a visit.
     */
    public function showImaging(Visit $visit): View
    {
        $visit->load(['patient', 'doctor', 'imagingStudies.orderedBy', 'imagingStudies.performedBy']);
        return view('visits.workspace.imaging', compact('visit'));
    }

    /**
     * Show treatments page for a visit.
     */
    public function showTreatments(Visit $visit): View
    {
        $visit->load(['patient', 'doctor', 'treatmentPlans']);
        return view('visits.workspace.treatments', compact('visit'));
    }

    /**
     * Show prescriptions page for a visit.
     */
    public function showPrescriptions(Visit $visit): View
    {
        $visit->load(['patient', 'doctor', 'prescriptions.doctor', 'prescriptions.prescriptionItems']);
        return view('visits.workspace.prescriptions', compact('visit'));
    }

    /**
     * Show spectacles page for a visit.
     */
    public function showSpectacles(Visit $visit): View
    {
        $visit->load(['patient', 'doctor', 'spectaclePrescriptions.doctor']);
        return view('visits.workspace.spectacles', compact('visit'));
    }

    // ===== WORKSPACE FORM CREATION METHODS =====

    /**
     * Show create refraction form.
     */
    public function createRefraction(Visit $visit): View
    {
        $visit->load(['patient', 'doctor']);
        return view('visits.workspace.refraction-create', compact('visit'));
    }

    /**
     * Show create imaging form.
     */
    public function createImaging(Visit $visit): View
    {
        $visit->load(['patient', 'doctor']);
        return view('visits.workspace.imaging-create', compact('visit'));
    }

    /**
     * Show create treatment form.
     */
    public function createTreatment(Visit $visit): View
    {
        $visit->load(['patient', 'doctor']);
        return view('visits.workspace.treatment-create', compact('visit'));
    }

    /**
     * Show create prescription form.
     */
    public function createPrescription(Visit $visit): View
    {
        $visit->load(['patient', 'doctor']);
        return view('visits.workspace.prescription-create', compact('visit'));
    }

    /**
     * Show create spectacles form.
     */
    public function createSpectacles(Visit $visit): View
    {
        $visit->load(['patient', 'doctor']);
        return view('visits.workspace.spectacles-create', compact('visit'));
    }

    // ===== WORKSPACE STORE METHODS (SIMPLIFIED) =====

    /**
     * Store or update anamnesis for a visit.
     */
    public function storeAnamnesis(Request $request, Visit $visit): RedirectResponse
    {
        $request->validate([
            'chief_complaint' => 'nullable|string',
            'history_of_present_illness' => 'nullable|string',
            'past_medical_history' => 'nullable|string',
            'family_history' => 'nullable|string',
            'medications_current' => 'nullable|string',
            'allergies' => 'nullable|string',
            'therapy_in_use' => 'nullable|string',
            'other_notes' => 'nullable|string',
        ]);

        $visit->anamnesis()->updateOrCreate(
            ['visit_id' => $visit->id],
            $request->only(['chief_complaint', 'history_of_present_illness', 'past_medical_history', 'family_history', 'medications_current', 'allergies', 'therapy_in_use', 'other_notes'])
        );

        return redirect()->route('visits.anamnesis', $visit)->with('success', his_trans('messages.anamnesis_saved'));
    }

    /**
     * Store or update ophthalmic exam for a visit.
     */
    public function storeExam(Request $request, Visit $visit): RedirectResponse
    {
        $request->validate([
            'visus_od' => 'nullable|string|max:50',
            'visus_os' => 'nullable|string|max:50',
            'iop_od' => 'nullable|numeric|between:0,99.99',
            'iop_os' => 'nullable|numeric|between:0,99.99',
            'anterior_segment_findings' => 'nullable|string',
            'posterior_segment_findings' => 'nullable|string',
        ]);

        $visit->ophthalmicExam()->updateOrCreate(
            ['visit_id' => $visit->id],
            $request->only(['visus_od', 'visus_os', 'iop_od', 'iop_os', 'anterior_segment_findings', 'posterior_segment_findings'])
        );

        return redirect()->route('visits.examination', $visit)->with('success', his_trans('messages.exam_saved'));
    }

    /**
     * Store a refraction measurement.
     */
    public function storeRefraction(Request $request, Visit $visit): RedirectResponse
    {
        $request->validate([
            'eye' => 'required|in:OD,OS',
            'method' => 'required|in:autorefraction,lensmeter,subjective',
            'sphere' => 'nullable|numeric|between:-30,30',
            'cylinder' => 'nullable|numeric|between:-10,10',
            'axis' => 'nullable|integer|between:1,180',
            'add_power' => 'nullable|numeric|between:0,5',
            'prism' => 'nullable|numeric|between:0,20',
            'base' => 'nullable|in:up,down,in,out',
            'notes' => 'nullable|string',
        ]);

        // Get or create the ophthalmic exam first
        $exam = $visit->ophthalmicExam()->firstOrCreate(['visit_id' => $visit->id]);

        $exam->refractions()->create($request->only(['eye', 'method', 'sphere', 'cylinder', 'axis', 'add_power', 'prism', 'base', 'notes']));

        return redirect()->route('visits.examination', $visit)->with('success', 'Refraction saved successfully.');
    }

    /**
     * Store an imaging study.
     */
    public function storeImaging(Request $request, Visit $visit): RedirectResponse
    {
        $request->validate([
            'modality' => 'required|in:OCT,VF,US,FA,Biometry,Photo,Other',
            'eye' => 'required|in:OD,OS,OU,NA',
            'status' => 'required|in:ordered,done,reported',
            'performed_at' => 'nullable|date',
            'findings' => 'nullable|string',
        ]);

        $data = $request->only(['modality', 'eye', 'status', 'performed_at', 'findings']);
        $data['ordered_by'] = Auth::id();
        if ($data['status'] !== 'ordered' && $data['performed_at']) {
            $data['performed_by'] = Auth::id();
        }

        $visit->imagingStudies()->create($data);

        return redirect()->route('visits.imaging', $visit)->with('success', 'Imaging study saved successfully.');
    }

    /**
     * Store a treatment plan.
     */
    public function storeTreatment(Request $request, Visit $visit): RedirectResponse
    {
        $request->validate([
            'plan_type' => 'required|in:surgery,injection,medical,advice',
            'recommendation' => 'required|string|max:255',
            'details' => 'nullable|string',
            'planned_date' => 'nullable|date',
            'status' => 'required|in:proposed,accepted,scheduled,done,declined',
        ]);

        $visit->treatmentPlans()->create($request->only(['plan_type', 'recommendation', 'details', 'planned_date', 'status']));

        return redirect()->route('visits.treatments', $visit)->with('success', 'Treatment plan saved successfully.');
    }

    /**
     * Store a prescription with items.
     */
    public function storePrescription(Request $request, Visit $visit): RedirectResponse
    {
        $request->validate([
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.drug_name' => 'required|string|max:255',
            'items.*.form' => 'required|in:drops,ointment,tablet,capsule,other',
            'items.*.strength' => 'nullable|string|max:50',
            'items.*.dosage_instructions' => 'required|string|max:255',
            'items.*.duration_days' => 'nullable|integer|min:1',
            'items.*.repeats' => 'nullable|integer|min:0',
        ]);

        $prescription = $visit->prescriptions()->create([
            'doctor_id' => Auth::id(),
            'notes' => $request->input('notes'),
        ]);

        foreach ($request->input('items', []) as $item) {
            $prescription->prescriptionItems()->create($item);
        }

        return redirect()->route('visits.prescriptions', $visit)->with('success', his_trans('messages.prescription_saved'));
    }

    /**
     * Delete a refraction measurement.
     */
    public function destroyRefraction(Visit $visit, Refraction $refraction): RedirectResponse
    {
        $refraction->delete();

        return redirect()->route('visits.examination', $visit)->with('success', 'Refraction deleted successfully.');
    }

    /**
     * Delete an imaging study.
     */
    public function destroyImaging(Visit $visit, ImagingStudy $imaging): RedirectResponse
    {
        $imaging->delete();

        return redirect()->route('visits.imaging', $visit)->with('success', 'Imaging study deleted successfully.');
    }

    /**
     * Delete a treatment plan.
     */
    public function destroyTreatment(Visit $visit, TreatmentPlan $treatment): RedirectResponse
    {
        $treatment->delete();

        return redirect()->route('visits.treatments', $visit)->with('success', 'Treatment plan deleted successfully.');
    }

    /**
     * Delete a prescription.
     */
    public function destroyPrescription(Visit $visit, Prescription $prescription): RedirectResponse
    {
        $prescription->delete(); // This will also delete related items due to cascade

        return redirect()->route('visits.prescriptions', $visit)->with('success', 'Prescription deleted successfully.');
    }

    /**
     * Store a spectacle prescription.
     */
    public function storeSpectacles(Request $request, Visit $visit): RedirectResponse
    {
        $request->validate([
            'type' => 'required|in:distance,near,bifocal,progressive',
            'od_sphere' => 'nullable|numeric|between:-30,30',
            'od_cylinder' => 'nullable|numeric|between:-10,10',
            'od_axis' => 'nullable|integer|between:1,180',
            'od_add' => 'nullable|numeric|between:0,5',
            'os_sphere' => 'nullable|numeric|between:-30,30',
            'os_cylinder' => 'nullable|numeric|between:-10,10',
            'os_axis' => 'nullable|integer|between:1,180',
            'os_add' => 'nullable|numeric|between:0,5',
            'pd_distance' => 'nullable|numeric|between:40,80',
            'pd_near' => 'nullable|numeric|between:40,80',
            'notes' => 'nullable|string',
            'valid_until' => 'nullable|date',
        ]);

        $data = $request->only(['type', 'od_sphere', 'od_cylinder', 'od_axis', 'od_add', 'os_sphere', 'os_cylinder', 'os_axis', 'os_add', 'pd_distance', 'pd_near', 'notes', 'valid_until']);
        $data['doctor_id'] = Auth::id();

        $visit->spectaclePrescriptions()->create($data);

        return redirect()->route('visits.spectacles', $visit)->with('success', 'Spectacle prescription saved successfully.');
    }

    /**
     * Delete a spectacle prescription.
     */
    public function destroySpectacles(Visit $visit, SpectaclePrescription $spectacle): RedirectResponse
    {
        $spectacle->delete();

        return redirect()->route('visits.spectacles', $visit)->with('success', 'Spectacle prescription deleted successfully.');
    }

    /**
     * Show edit imaging form.
     */
    public function editImaging(Visit $visit, ImagingStudy $imaging): View
    {
        $visit->load(['patient', 'doctor']);
        return view('visits.workspace.imaging-edit', compact('visit', 'imaging'));
    }

    /**
     * Show edit treatment form.
     */
    public function editTreatment(Visit $visit, TreatmentPlan $treatment): View
    {
        $visit->load(['patient', 'doctor']);
        return view('visits.workspace.treatment-edit', compact('visit', 'treatment'));
    }

    /**
     * Show edit prescription form.
     */
    public function editPrescription(Visit $visit, Prescription $prescription): View
    {
        $visit->load(['patient', 'doctor']);
        $prescription->load('prescriptionItems');
        return view('visits.workspace.prescription-edit', compact('visit', 'prescription'));
    }

    /**
     * Show edit spectacles form.
     */
    public function editSpectacles(Visit $visit, SpectaclePrescription $spectacle): View
    {
        $visit->load(['patient', 'doctor']);
        return view('visits.workspace.spectacles-edit', compact('visit', 'spectacle'));
    }

    // ===== WORKSPACE UPDATE METHODS =====

    /**
     * Update an imaging study.
     */
    public function updateImaging(Request $request, Visit $visit, ImagingStudy $imaging): RedirectResponse
    {
        $request->validate([
            'modality' => 'required|in:OCT,VF,US,FA,Biometry,Photo,Other',
            'eye' => 'required|in:OD,OS,OU,NA',
            'status' => 'required|in:ordered,done,reported',
            'performed_at' => 'nullable|date',
            'findings' => 'nullable|string',
        ]);

        $data = $request->only(['modality', 'eye', 'status', 'performed_at', 'findings']);
        if ($data['status'] !== 'ordered' && $data['performed_at']) {
            $data['performed_by'] = Auth::id();
        }

        $imaging->update($data);

        return redirect()->route('visits.imaging', $visit)->with('success', 'Imaging study updated successfully.');
    }

    /**
     * Update a treatment plan.
     */
    public function updateTreatment(Request $request, Visit $visit, TreatmentPlan $treatment): RedirectResponse
    {
        $request->validate([
            'plan_type' => 'required|in:surgery,injection,medical,advice',
            'recommendation' => 'required|string|max:255',
            'details' => 'nullable|string',
            'planned_date' => 'nullable|date',
            'status' => 'required|in:proposed,accepted,scheduled,done,declined',
        ]);

        $treatment->update($request->only(['plan_type', 'recommendation', 'details', 'planned_date', 'status']));

        return redirect()->route('visits.treatments', $visit)->with('success', 'Treatment plan updated successfully.');
    }

    /**
     * Update a prescription with items.
     */
    public function updatePrescription(Request $request, Visit $visit, Prescription $prescription): RedirectResponse
    {
        $request->validate([
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.drug_name' => 'required|string|max:255',
            'items.*.form' => 'required|in:drops,ointment,tablet,capsule,other',
            'items.*.strength' => 'nullable|string|max:50',
            'items.*.dosage_instructions' => 'required|string|max:255',
            'items.*.duration_days' => 'nullable|integer|min:1',
            'items.*.repeats' => 'nullable|integer|min:0',
        ]);

        $prescription->update([
            'notes' => $request->input('notes'),
        ]);

        // Delete existing items and recreate them
        $prescription->prescriptionItems()->delete();

        foreach ($request->input('items', []) as $item) {
            $prescription->prescriptionItems()->create($item);
        }

        return redirect()->route('visits.prescriptions', $visit)->with('success', his_trans('messages.prescription_saved'));
    }

    /**
     * Update a spectacle prescription.
     */
    public function updateSpectacles(Request $request, Visit $visit, SpectaclePrescription $spectacle): RedirectResponse
    {
        $request->validate([
            'type' => 'required|in:distance,near,bifocal,progressive',
            'od_sphere' => 'nullable|numeric|between:-30,30',
            'od_cylinder' => 'nullable|numeric|between:-10,10',
            'od_axis' => 'nullable|integer|between:1,180',
            'od_add' => 'nullable|numeric|between:0,5',
            'os_sphere' => 'nullable|numeric|between:-30,30',
            'os_cylinder' => 'nullable|numeric|between:-10,10',
            'os_axis' => 'nullable|integer|between:1,180',
            'os_add' => 'nullable|numeric|between:0,5',
            'pd_distance' => 'nullable|numeric|between:40,80',
            'pd_near' => 'nullable|numeric|between:40,80',
            'notes' => 'nullable|string',
            'valid_until' => 'nullable|date',
        ]);

        $spectacle->update($request->only(['type', 'od_sphere', 'od_cylinder', 'od_axis', 'od_add', 'os_sphere', 'os_cylinder', 'os_axis', 'os_add', 'pd_distance', 'pd_near', 'notes', 'valid_until']));

        return redirect()->route('visits.spectacles', $visit)->with('success', 'Spectacle prescription updated successfully.');
    }
}
