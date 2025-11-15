<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('visits.copy_from_previous_visit') }} - {{ $visit->patient->full_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <!-- Header Information -->
                    <div class="mb-8">
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                                        {{ __('visits.copy_instruction') }}
                                    </h3>
                                    <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                                        <p>{{ __('visits.copy_description') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('visits.process_copy', [$visit, $previousVisit]) }}" method="POST" id="copyForm">
                        @csrf

                        <!-- Error Display -->
                        @if ($errors->any())
                            <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                                            {{ __('common.validation_errors') }}
                                        </h3>
                                        <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                                            <ul class="list-disc list-inside space-y-1">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="space-y-8">
                            <!-- Medical History Section -->
                            @if($previousVisit->anamnesis)
                            <div class="bg-gray-50 dark:bg-gray-900/20 rounded-lg p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('workspace.anamnesis') }}</h3>
                                    <button type="button" onclick="toggleSection('medical_history')" class="text-sm text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">
                                        {{ __('common.select_all') }}
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 gap-4">
                                    @if($previousVisit->anamnesis->past_medical_history)
                                    <label class="flex items-start">
                                        <input type="checkbox" name="medical_history[]" value="past_medical_history" class="mt-1 rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ __('anamnesis.past_medical_history') }}</div>
                                            <div class="text-sm text-gray-600 dark:text-gray-400">{{ Str::limit($previousVisit->anamnesis->past_medical_history, 100) }}</div>
                                        </div>
                                    </label>
                                    @endif

                                    @if($previousVisit->anamnesis->family_history)
                                    <label class="flex items-start">
                                        <input type="checkbox" name="medical_history[]" value="family_history" class="mt-1 rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ __('anamnesis.family_history') }}</div>
                                            <div class="text-sm text-gray-600 dark:text-gray-400">{{ Str::limit($previousVisit->anamnesis->family_history, 100) }}</div>
                                        </div>
                                    </label>
                                    @endif

                                    @if($previousVisit->anamnesis->medications_current)
                                    <label class="flex items-start">
                                        <input type="checkbox" name="medical_history[]" value="medications_current" class="mt-1 rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ __('anamnesis.medications_current') }}</div>
                                            <div class="text-sm text-gray-600 dark:text-gray-400">{{ Str::limit($previousVisit->anamnesis->medications_current, 100) }}</div>
                                        </div>
                                    </label>
                                    @endif

                                    @if($previousVisit->anamnesis->allergies)
                                    <label class="flex items-start">
                                        <input type="checkbox" name="medical_history[]" value="allergies" class="mt-1 rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ __('anamnesis.allergies') }}</div>
                                            <div class="text-sm text-gray-600 dark:text-gray-400">{{ Str::limit($previousVisit->anamnesis->allergies, 100) }}</div>
                                        </div>
                                    </label>
                                    @endif

                                    @if($previousVisit->anamnesis->therapy_in_use)
                                    <label class="flex items-start">
                                        <input type="checkbox" name="medical_history[]" value="therapy_in_use" class="mt-1 rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ __('anamnesis.therapy_in_use') }}</div>
                                            <div class="text-sm text-gray-600 dark:text-gray-400">{{ Str::limit($previousVisit->anamnesis->therapy_in_use, 100) }}</div>
                                        </div>
                                    </label>
                                    @endif
                                </div>
                            </div>
                            @endif

                            <!-- Diagnoses Section -->
                            @if($previousVisit->diagnoses->isNotEmpty())
                            <div class="bg-gray-50 dark:bg-gray-900/20 rounded-lg p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('visits.diagnoses') }}</h3>
                                    <button type="button" onclick="toggleSection('diagnoses')" class="text-sm text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">
                                        {{ __('common.select_all') }}
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 gap-4">
                                    @foreach($previousVisit->diagnoses as $diagnosis)
                                    <label class="flex items-start">
                                        <input type="checkbox" name="diagnoses[]" value="{{ $diagnosis->id }}" class="mt-1 rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $diagnosis->term }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $diagnosis->code }} • {{ $diagnosis->eye_display }} • {{ $diagnosis->status_display }}
                                                @if($diagnosis->is_primary)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300">
                                                        {{ __('diagnoses.primary') }}
                                                    </span>
                                                @endif
                                            </div>
                                            @if($diagnosis->notes)
                                                <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ Str::limit($diagnosis->notes, 80) }}</div>
                                            @endif
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <!-- Examination Data Section -->
                            @if($previousVisit->ophthalmicExam)
                            <div class="bg-gray-50 dark:bg-gray-900/20 rounded-lg p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('workspace.examination') }}</h3>
                                    <button type="button" onclick="toggleSection('examination_data')" class="text-sm text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">
                                        {{ __('common.select_all') }}
                                    </button>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    @if($previousVisit->ophthalmicExam->visus_od)
                                    <label class="flex items-center">
                                        <input type="checkbox" name="examination_data[]" value="visus_od" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ __('examination.visus_od') }}</div>
                                            <div class="text-sm text-gray-600 dark:text-gray-400">{{ $previousVisit->ophthalmicExam->visus_od }}</div>
                                        </div>
                                    </label>
                                    @endif

                                    @if($previousVisit->ophthalmicExam->visus_os)
                                    <label class="flex items-center">
                                        <input type="checkbox" name="examination_data[]" value="visus_os" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ __('examination.visus_os') }}</div>
                                            <div class="text-sm text-gray-600 dark:text-gray-400">{{ $previousVisit->ophthalmicExam->visus_os }}</div>
                                        </div>
                                    </label>
                                    @endif

                                    @if($previousVisit->ophthalmicExam->iop_od)
                                    <label class="flex items-center">
                                        <input type="checkbox" name="examination_data[]" value="iop_od" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ __('examination.iop_od') }}</div>
                                            <div class="text-sm text-gray-600 dark:text-gray-400">{{ $previousVisit->ophthalmicExam->iop_od }} mmHg</div>
                                        </div>
                                    </label>
                                    @endif

                                    @if($previousVisit->ophthalmicExam->iop_os)
                                    <label class="flex items-center">
                                        <input type="checkbox" name="examination_data[]" value="iop_os" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ __('examination.iop_os') }}</div>
                                            <div class="text-sm text-gray-600 dark:text-gray-400">{{ $previousVisit->ophthalmicExam->iop_os }} mmHg</div>
                                        </div>
                                    </label>
                                    @endif
                                </div>

                                <!-- Refractions Subsection -->
                                @if($previousVisit->ophthalmicExam->refractions->isNotEmpty())
                                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center justify-between mb-4">
                                        <h4 class="text-md font-medium text-gray-900 dark:text-white">{{ __('refraction.title') }}</h4>
                                        <button type="button" onclick="toggleSection('refractions')" class="text-sm text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">
                                            {{ __('common.select_all') }}
                                        </button>
                                    </div>

                                    <div class="grid grid-cols-1 gap-3">
                                        @foreach($previousVisit->ophthalmicExam->refractions as $refraction)
                                        <label class="flex items-start">
                                            <input type="checkbox" name="refractions[]" value="{{ $refraction->id }}" class="mt-1 rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $refraction->eye }} - {{ $refraction->method }}</div>
                                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                                    SPH: {{ $refraction->sphere ?? '—' }} CYL: {{ $refraction->cylinder ?? '—' }} AXIS: {{ $refraction->axis ?? '—' }}
                                                    @if($refraction->add_power) ADD: {{ $refraction->add_power }} @endif
                                                </div>
                                            </div>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                            @endif

                            <!-- Prescriptions Section -->
                            @if($previousVisit->prescriptions->isNotEmpty())
                            <div class="bg-gray-50 dark:bg-gray-900/20 rounded-lg p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('workspace.prescriptions') }}</h3>
                                    <button type="button" onclick="toggleSection('prescriptions')" class="text-sm text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">
                                        {{ __('common.select_all') }}
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 gap-4">
                                    @foreach($previousVisit->prescriptions as $prescription)
                                    <label class="flex items-start">
                                        <input type="checkbox" name="prescriptions[]" value="{{ $prescription->id }}" class="mt-1 rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                                        <div class="ml-3 flex-1">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ __('prescriptions.title') }} #{{ $prescription->id }}</div>
                                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ $prescription->prescriptionItems->count() }} {{ __('prescriptions.medications') }}
                                                @if($prescription->prescriptionItems->isNotEmpty())
                                                    - {{ $prescription->prescriptionItems->pluck('drug_name')->take(2)->implode(', ') }}
                                                    @if($prescription->prescriptionItems->count() > 2)...@endif
                                                @endif
                                            </div>
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <!-- Spectacle Prescriptions Section -->
                            @if($previousVisit->spectaclePrescriptions->isNotEmpty())
                            <div class="bg-gray-50 dark:bg-gray-900/20 rounded-lg p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('workspace.spectacles') }}</h3>
                                    <button type="button" onclick="toggleSection('spectacle_prescriptions')" class="text-sm text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">
                                        {{ __('common.select_all') }}
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 gap-4">
                                    @foreach($previousVisit->spectaclePrescriptions as $spectacle)
                                    <label class="flex items-start">
                                        <input type="checkbox" name="spectacle_prescriptions[]" value="{{ $spectacle->id }}" class="mt-1 rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ __('spectacles.title') }} - {{ ucfirst($spectacle->type) }}</div>
                                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                                OD: {{ $spectacle->od_sphere ?? '—' }}/{{ $spectacle->od_cylinder ?? '—' }}/{{ $spectacle->od_axis ?? '—' }}
                                                OS: {{ $spectacle->os_sphere ?? '—' }}/{{ $spectacle->os_cylinder ?? '—' }}/{{ $spectacle->os_axis ?? '—' }}
                                                @if($spectacle->valid_until)
                                                    <br>{{ __('spectacles.valid_until') }}: {{ $spectacle->valid_until->format('M d, Y') }}
                                                @endif
                                            </div>
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <!-- Treatment Plans Section -->
                            @if($previousVisit->treatmentPlans->isNotEmpty())
                            <div class="bg-gray-50 dark:bg-gray-900/20 rounded-lg p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('workspace.treatments') }}</h3>
                                    <button type="button" onclick="toggleSection('treatment_plans')" class="text-sm text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">
                                        {{ __('common.select_all') }}
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 gap-4">
                                    @foreach($previousVisit->treatmentPlans as $plan)
                                    <label class="flex items-start">
                                        <input type="checkbox" name="treatment_plans[]" value="{{ $plan->id }}" class="mt-1 rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ ucfirst($plan->plan_type) }}</div>
                                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ Str::limit($plan->details, 100) }}
                                                @if($plan->planned_date)
                                                    <br>{{ __('treatments.planned_date') }}: {{ $plan->planned_date->format('M d, Y') }}
                                                @endif
                                            </div>
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-8 flex items-center justify-between">
                            <a href="{{ route('visits.show', $visit) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600">
                                ← {{ __('common.cancel') }}
                            </a>

                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-600">
                                {{ __('visits.copy_selected_data') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleSection(sectionName) {
            const checkboxes = document.querySelectorAll(`input[name="${sectionName}[]"]`);
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);

            checkboxes.forEach(checkbox => {
                checkbox.checked = !allChecked;
            });
        }

        // Add loading state to form submission
        document.getElementById('copyForm').addEventListener('submit', function(e) {
            const submitButton = e.target.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.innerHTML = '{{ __("common.processing") }}...';
        });
    </script>
    @endpush
</x-app-layout>
