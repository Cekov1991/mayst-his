@php
    $prescriptions = $visit->prescriptions;
@endphp

<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ his_trans('workspace.prescriptions') }}</h3>
        <button onclick="document.getElementById('prescription-modal').classList.remove('hidden')"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-600">
            + {{ his_trans('prescriptions.add_prescription') }}
        </button>
    </div>

    @if (session('prescription_success'))
        <div class="rounded-md bg-green-50 dark:bg-green-900/20 p-4">
            <div class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('prescription_success') }}</div>
        </div>
    @endif

    @if($prescriptions->isNotEmpty())
        @foreach($prescriptions as $prescription)
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h4 class="text-md font-medium text-gray-900 dark:text-white">
                            Prescription #{{ $prescription->id }}
                        </h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Prescribed by {{ $prescription->doctor->name }} on {{ $prescription->created_at->format('M d, Y') }}
                        </p>
                        @if($prescription->notes)
                            <p class="text-sm text-gray-600 dark:text-gray-300 mt-2">{{ $prescription->notes }}</p>
                        @endif
                    </div>
                    <div class="flex space-x-2">
                        <button onclick="editPrescription({{ $prescription->id }})"
                                class="text-sm font-medium text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                            Edit
                        </button>
                        <form action="{{ route('visits.prescriptions.destroy', [$visit, $prescription]) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Are you sure?')"
                                    class="text-sm font-medium text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>

                @if($prescription->prescriptionItems->isNotEmpty())
                    <div class="mt-4">
                        <h5 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Medications:</h5>
                        <div class="space-y-3">
                            @foreach($prescription->prescriptionItems as $item)
                                <div class="flex justify-between items-start p-3 bg-gray-50 dark:bg-gray-900/20 rounded-md">
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-900 dark:text-white">
                                            {{ $item->drug_name }}
                                            @if($item->strength)
                                                <span class="text-gray-500 dark:text-gray-400">({{ $item->strength }})</span>
                                            @endif
                                        </div>
                                        <div class="text-sm text-gray-600 dark:text-gray-300">
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300 mr-2">
                                                {{ his_trans("drug_forms.{$item->form}") }}
                                            </span>
                                            {{ $item->dosage_instructions }}
                                        </div>
                                        @if($item->duration_days)
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                Duration: {{ $item->duration_days }} days
                                                @if($item->repeats) • Repeats: {{ $item->repeats }} @endif
                                            </div>
                                        @endif
                                    </div>
                                    <button onclick="deleteItem({{ $item->id }})"
                                            class="ml-4 text-red-400 hover:text-red-600 dark:text-red-500 dark:hover:text-red-400">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="text-center py-4 text-gray-500 dark:text-gray-400">
                        <p class="text-sm">No medications in this prescription</p>
                    </div>
                @endif
            </div>
        @endforeach
    @else
        <div class="text-center py-12 text-gray-500 dark:text-gray-400">
            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No prescriptions</h3>
            <p class="text-gray-500 dark:text-gray-400 mb-6">Get started by creating a prescription for this visit.</p>
        </div>
    @endif

    <!-- Add Prescription Modal -->
    <div id="prescription-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-4/5 lg:w-3/5 shadow-lg rounded-md bg-white dark:bg-gray-800 max-h-screen overflow-y-auto">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ his_trans('prescriptions.add_prescription') }}</h3>
                    <button onclick="closePrescriptionModal()"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form action="{{ route('visits.prescriptions.store', $visit) }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Prescription Notes -->
                    <div>
                        <label for="prescription_notes" class="block text-sm font-medium text-gray-900 dark:text-white">{{ his_trans('prescriptions.notes') }}</label>
                        <textarea name="notes" id="prescription_notes" rows="2"
                                  class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                  placeholder="General prescription notes..."></textarea>
                    </div>

                    <!-- Medication Items -->
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <label class="block text-sm font-medium text-gray-900 dark:text-white">Medications</label>
                            <button type="button" onclick="addMedicationRow()"
                                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-900/20 dark:text-indigo-300 dark:hover:bg-indigo-900/40">
                                + Add Medication
                            </button>
                        </div>

                        <div id="medication-items" class="space-y-4">
                            <!-- Initial medication row -->
                            <div class="medication-item p-4 bg-gray-50 dark:bg-gray-900/20 rounded-lg">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Drug Name</label>
                                        <input type="text" name="items[0][drug_name]" required
                                               class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Form</label>
                                        <select name="items[0][form]" required
                                                class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:focus:ring-indigo-500">
                                            <option value="drops">Eye Drops</option>
                                            <option value="ointment">Ointment</option>
                                            <option value="tablet">Tablet</option>
                                            <option value="capsule">Capsule</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Strength</label>
                                        <input type="text" name="items[0][strength]"
                                               class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                               placeholder="e.g. 0.5mg">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dosage Instructions</label>
                                        <input type="text" name="items[0][dosage_instructions]" required
                                               class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                               placeholder="e.g. 1 drop twice daily">
                                    </div>
                                    <div class="flex justify-between items-end">
                                        <div class="flex space-x-2">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Duration (days)</label>
                                                <input type="number" name="items[0][duration_days]" min="1"
                                                       class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Repeats</label>
                                                <input type="number" name="items[0][repeats]" min="0"
                                                       class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500">
                                            </div>
                                        </div>
                                        <button type="button" onclick="removeMedicationRow(this)"
                                                class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="closePrescriptionModal()"
                                class="px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-600">
                            Save Prescription
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
let medicationCount = 1;

function closePrescriptionModal() {
    document.getElementById('prescription-modal').classList.add('hidden');
    document.querySelector('#prescription-modal form').reset();
    // Reset medication items to just one
    document.getElementById('medication-items').innerHTML = `
        <div class="medication-item p-4 bg-gray-50 dark:bg-gray-900/20 rounded-lg">
            <!-- Initial medication row content -->
        </div>
    `;
    medicationCount = 1;
}

function addMedicationRow() {
    const container = document.getElementById('medication-items');
    const newRow = document.createElement('div');
    newRow.className = 'medication-item p-4 bg-gray-50 dark:bg-gray-900/20 rounded-lg';
    newRow.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Drug Name</label>
                <input type="text" name="items[${medicationCount}][drug_name]" required
                       class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500">
            </div>
            <!-- ... other fields ... -->
        </div>
    `;
    container.appendChild(newRow);
    medicationCount++;
}

function removeMedicationRow(button) {
    if (document.querySelectorAll('.medication-item').length > 1) {
        button.closest('.medication-item').remove();
    }
}

function editPrescription(id) {
    document.getElementById('prescription-modal').classList.remove('hidden');
}

function deleteItem(id) {
    if (confirm('Are you sure you want to delete this medication?')) {
        // Implement AJAX delete for prescription item
    }
}
</script>
