<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PatientController extends Controller
{
    /**
     * Display a listing of patients.
     */
    public function index(Request $request): View
    {
        $query = Patient::query();

        // Handle search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%")
                  ->orWhere('unique_master_citizen_number', 'LIKE', "%{$search}%");
            });
        }

        // Handle filter by sex
        if ($sex = $request->get('sex')) {
            $query->where('sex', $sex);
        }

        $patients = $query->orderBy('last_name')
            ->orderBy('first_name')
            ->paginate(15)
            ->withQueryString();

        return view('patients.index', compact('patients'));
    }

    /**
     * Show the form for creating a new patient.
     */
    public function create(): View
    {
        return view('patients.create');
    }

    /**
     * Store a newly created patient in storage.
     */
    public function store(StorePatientRequest $request): RedirectResponse
    {
        $patient = Patient::create($request->validated());

        return redirect()
            ->route('patients.show', $patient)
            ->with('success', __('his.messages.patient_created'));
    }

    /**
     * Display the specified patient.
     */
    public function show(Patient $patient): View
    {
        // Load visits with relationships for display
        $patient->load([
            'visits' => function ($query) {
                $query->with(['doctor'])
                      ->orderBy('scheduled_at', 'desc')
                      ->limit(10);
            }
        ]);

        return view('patients.show', compact('patient'));
    }

    /**
     * Show the form for editing the specified patient.
     */
    public function edit(Patient $patient): View
    {
        return view('patients.edit', compact('patient'));
    }

    /**
     * Update the specified patient in storage.
     */
    public function update(UpdatePatientRequest $request, Patient $patient): RedirectResponse
    {
        $patient->update($request->validated());

        return redirect()
            ->route('patients.show', $patient)
            ->with('success', __('his.messages.patient_updated'));
    }

    /**
     * Remove the specified patient from storage.
     */
    public function destroy(Patient $patient): RedirectResponse
    {
        // Check if patient has any visits before deletion
        if ($patient->visits()->exists()) {
            return redirect()
                ->route('patients.show', $patient)
                ->with('error', 'Cannot delete patient with existing visits. Please archive instead.');
        }

        $patient->delete();

        return redirect()
            ->route('patients.index')
            ->with('success', __('his.messages.patient_deleted'));
    }

    /**
     * Get patient suggestions for autocomplete (AJAX endpoint)
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $patients = Patient::where(function ($q) use ($query) {
            $q->where('first_name', 'LIKE', "%{$query}%")
              ->orWhere('last_name', 'LIKE', "%{$query}%")
              ->orWhere('unique_master_citizen_number', 'LIKE', "%{$query}%");
        })
        ->limit(10)
        ->get()
        ->map(function ($patient) {
            return [
                'id' => $patient->id,
                'text' => $patient->full_name,
                'subtitle' => $patient->unique_master_citizen_number ?: $patient->dob->format('Y-m-d'),
            ];
        });

        return response()->json($patients);
    }
}
