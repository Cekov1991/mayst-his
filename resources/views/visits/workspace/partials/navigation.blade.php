<!-- Workspace Navigation -->
<div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg">
    <div class="border-b border-gray-200 dark:border-gray-700">
        <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
            <a href="{{ route('visits.anamnesis', $visit) }}"
               class="@if($active === 'anamnesis') border-indigo-500 text-indigo-600 dark:text-indigo-400 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                {{ __('his.workspace.anamnesis') }}
                @if($visit->anamnesis)
                    <span class="ml-1 text-green-500">✓</span>
                @endif
            </a>

            <a href="{{ route('visits.examination', $visit) }}"
               class="@if($active === 'examination') border-indigo-500 text-indigo-600 dark:text-indigo-400 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                {{ __('his.workspace.examination') }}
                @if($visit->ophthalmicExam)
                    <span class="ml-1 text-green-500">✓</span>
                @endif
            </a>

            <a href="{{ route('visits.imaging', $visit) }}"
               class="@if($active === 'imaging') border-indigo-500 text-indigo-600 dark:text-indigo-400 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                {{ __('his.workspace.imaging') }}
                @if($visit->imagingStudies->isNotEmpty())
                    <span class="ml-1 text-gray-500">({{ $visit->imagingStudies->count() }})</span>
                @endif
            </a>

            <a href="{{ route('visits.treatments', $visit) }}"
               class="@if($active === 'treatments') border-indigo-500 text-indigo-600 dark:text-indigo-400 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                {{ __('his.workspace.treatment') }}
                @if($visit->treatmentPlans->isNotEmpty())
                    <span class="ml-1 text-gray-500">({{ $visit->treatmentPlans->count() }})</span>
                @endif
            </a>

            <a href="{{ route('visits.prescriptions', $visit) }}"
               class="@if($active === 'prescriptions') border-indigo-500 text-indigo-600 dark:text-indigo-400 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                {{ __('his.workspace.prescriptions') }}
                @if($visit->prescriptions->isNotEmpty())
                    <span class="ml-1 text-gray-500">({{ $visit->prescriptions->count() }})</span>
                @endif
            </a>

            <a href="{{ route('visits.spectacles', $visit) }}"
               class="@if($active === 'spectacles') border-indigo-500 text-indigo-600 dark:text-indigo-400 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                {{ __('his.workspace.spectacles') }}
                @if($visit->spectaclePrescriptions->isNotEmpty())
                    <span class="ml-1 text-gray-500">({{ $visit->spectaclePrescriptions->count() }})</span>
                @endif
            </a>
        </nav>
    </div>

    <!-- Quick Info Bar -->
    <div class="px-6 py-3 bg-gray-50 dark:bg-gray-900/20 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between text-sm">
            <div class="flex items-center space-x-4 text-gray-600 dark:text-gray-400">
                <span><strong>Patient:</strong> {{ $visit->patient->full_name }}</span>
                <span><strong>Type:</strong> {{ __("his.visit_types.{$visit->type}") }}</span>
                <span><strong>Status:</strong>
                    <span class="@if($visit->status === 'in_progress') text-blue-600 dark:text-blue-400 @elseif($visit->status === 'completed') text-green-600 dark:text-green-400 @else text-gray-600 dark:text-gray-400 @endif">
                        {{ __("his.visit_status.{$visit->status}") }}
                    </span>
                </span>
            </div>
            <a href="{{ route('visits.show', $visit) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                ← Back to Overview
            </a>
        </div>
    </div>
</div>
