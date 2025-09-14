<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit Prescription - {{ $visit->patient->full_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Workspace Navigation -->
            @include('visits.workspace.partials.navigation', ['visit' => $visit, 'active' => 'prescriptions'])

            <!-- Prescription Form -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Edit Prescription</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Update the prescription details and medications.</p>
                    </div>

                    <!-- Prescription Form -->
                    <form action="{{ route('visits.prescriptions.update', [$visit, $prescription]) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Prescription Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-900 dark:text-white">{{ his_trans('prescription.notes') }}</label>
                            <textarea name="notes" id="notes" rows="2"
                                      class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                      placeholder="General prescription notes...">{{ old('notes', $prescription->notes) }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
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
                                @forelse($prescription->prescriptionItems as $index => $item)
                                    <div class="medication-item p-4 bg-gray-50 dark:bg-gray-900/20 rounded-lg">
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Drug Name</label>
                                                <input type="text" name="items[{{ $index }}][drug_name]" value="{{ old("items.{$index}.drug_name", $item->drug_name) }}" required
                                                       class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Form</label>
                                                <select name="items[{{ $index }}][form]" required
                                                        class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:focus:ring-indigo-500">
                                                    <option value="drops" {{ old("items.{$index}.form", $item->form) === 'drops' ? 'selected' : '' }}>Eye Drops</option>
                                                    <option value="ointment" {{ old("items.{$index}.form", $item->form) === 'ointment' ? 'selected' : '' }}>Ointment</option>
                                                    <option value="tablet" {{ old("items.{$index}.form", $item->form) === 'tablet' ? 'selected' : '' }}>Tablet</option>
                                                    <option value="capsule" {{ old("items.{$index}.form", $item->form) === 'capsule' ? 'selected' : '' }}>Capsule</option>
                                                    <option value="other" {{ old("items.{$index}.form", $item->form) === 'other' ? 'selected' : '' }}>Other</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Strength</label>
                                                <input type="text" name="items[{{ $index }}][strength]" value="{{ old("items.{$index}.strength", $item->strength) }}"
                                                       class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                                       placeholder="e.g. 0.5mg">
                                            </div>
                                            <div class="md:col-span-2">
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dosage Instructions</label>
                                                <input type="text" name="items[{{ $index }}][dosage_instructions]" value="{{ old("items.{$index}.dosage_instructions", $item->dosage_instructions) }}" required
                                                       class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                                       placeholder="e.g. 1 drop twice daily">
                                            </div>
                                            <div class="flex justify-between items-end">
                                                <div class="flex space-x-2">
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Duration (days)</label>
                                                        <input type="number" name="items[{{ $index }}][duration_days]" value="{{ old("items.{$index}.duration_days", $item->duration_days) }}" min="1"
                                                               class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500">
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Repeats</label>
                                                        <input type="number" name="items[{{ $index }}][repeats]" value="{{ old("items.{$index}.repeats", $item->repeats) }}" min="0"
                                                               class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500">
                                                    </div>
                                                </div>
                                                <button type="button" onclick="removeMedicationRow(this)"
                                                        class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 disabled:opacity-50 disabled:cursor-not-allowed">
                                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <!-- Initial medication row if no items exist -->
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
                                                        class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 disabled:opacity-50 disabled:cursor-not-allowed">
                                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-between pt-6">
                            <a href="{{ route('visits.prescriptions', $visit) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600">
                                ← {{ his_trans('back') }}
                            </a>

                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-600">
                                {{ his_trans('update') }} Prescription
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    let medicationCount = {{ max(0, count($prescription->prescriptionItems) - 1) }};

    function addMedicationRow() {
        medicationCount++;
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
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Form</label>
                    <select name="items[${medicationCount}][form]" required
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
                    <input type="text" name="items[${medicationCount}][strength]"
                           class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                           placeholder="e.g. 0.5mg">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dosage Instructions</label>
                    <input type="text" name="items[${medicationCount}][dosage_instructions]" required
                           class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                           placeholder="e.g. 1 drop twice daily">
                </div>
                <div class="flex justify-between items-end">
                    <div class="flex space-x-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Duration (days)</label>
                            <input type="number" name="items[${medicationCount}][duration_days]" min="1"
                                   class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Repeats</label>
                            <input type="number" name="items[${medicationCount}][repeats]" min="0"
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
        `;
        container.appendChild(newRow);
        updateRemoveButtons();
    }

    function removeMedicationRow(button) {
        if (document.querySelectorAll('.medication-item').length > 1) {
            button.closest('.medication-item').remove();
            updateRemoveButtons();
        }
    }

    function updateRemoveButtons() {
        const items = document.querySelectorAll('.medication-item');
        items.forEach((item, index) => {
            const removeButton = item.querySelector('button[onclick*="removeMedicationRow"]');
            if (removeButton) {
                removeButton.disabled = items.length === 1;
            }
        });
    }

    // Initialize remove button states
    document.addEventListener('DOMContentLoaded', function() {
        updateRemoveButtons();
    });
    </script>
</x-app-layout>
