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
}
