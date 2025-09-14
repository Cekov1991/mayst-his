<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ his_trans('workspace.title') }} - {{ $visit->patient->full_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Visit Overview Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ his_trans('visits.visit_details') }}</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Visit information and status</p>
                        </div>

                        <!-- Quick Status Update Buttons -->
                        <div class="flex space-x-2">
                            @if($visit->status === 'scheduled')
                                <form action="{{ route('visits.updateStatus', $visit) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="arrived">
                                    <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 dark:bg-yellow-500 dark:hover:bg-yellow-600">
                                        {{ his_trans('visit_status.mark_arrived') }}
                                    </button>
                                </form>
                            @endif

                            @if($visit->status === 'arrived')
                                <form action="{{ route('visits.updateStatus', $visit) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="in_progress">
                                    <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600">
                                        {{ his_trans('visit_status.start') }}
                                    </button>
                                </form>
                            @endif

                            @if($visit->status === 'in_progress')
                                <form action="{{ route('visits.updateStatus', $visit) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="completed">
                                    <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:bg-green-500 dark:hover:bg-green-600">
                                        {{ his_trans('visit_status.complete') }}
                                    </button>
                                </form>
                            @endif

                            <a href="{{ route('visits.edit', $visit) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600">
                                {{ his_trans('edit') }}
                            </a>
                        </div>
                    </div>

                    <!-- Visit Details Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ his_trans('visits.patient') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                <a href="{{ route('patients.show', $visit->patient) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                    {{ $visit->patient->full_name }}
                                </a>
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ his_trans('visits.type') }}</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                    @if($visit->type === 'exam') bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300
                                    @elseif($visit->type === 'control') bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300
                                    @else bg-purple-100 text-purple-800 dark:bg-purple-900/20 dark:text-purple-300 @endif">
                                    {{ his_trans("visit_types.{$visit->type}") }}
                                </span>
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ his_trans('visits.status') }}</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                    @if($visit->status === 'scheduled') bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-300
                                    @elseif($visit->status === 'arrived') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300
                                    @elseif($visit->status === 'in_progress') bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300
                                    @elseif($visit->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300
                                    @else bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300 @endif">
                                    {{ his_trans("visit_status.{$visit->status}") }}
                                </span>
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ his_trans('visits.scheduled_at') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $visit->scheduled_at->format('M d, Y g:i A') }}</dd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Workspace Navigation -->
            <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg">
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                        <a href="{{ route('visits.anamnesis', $visit) }}"
                           class="@if(request()->routeIs('visits.anamnesis')) border-indigo-500 text-indigo-600 dark:text-indigo-400 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            {{ his_trans('workspace.anamnesis') }}
                        </a>

                        <a href="{{ route('visits.examination', $visit) }}"
                           class="@if(request()->routeIs('visits.examination*')) border-indigo-500 text-indigo-600 dark:text-indigo-400 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            {{ his_trans('workspace.examination') }}
                        </a>

                        <a href="{{ route('visits.imaging', $visit) }}"
                           class="@if(request()->routeIs('visits.imaging*')) border-indigo-500 text-indigo-600 dark:text-indigo-400 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            {{ his_trans('workspace.imaging') }}
                        </a>

                        <a href="{{ route('visits.treatments', $visit) }}"
                           class="@if(request()->routeIs('visits.treatments*')) border-indigo-500 text-indigo-600 dark:text-indigo-400 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            {{ his_trans('workspace.treatment') }}
                        </a>

                        <a href="{{ route('visits.prescriptions', $visit) }}"
                           class="@if(request()->routeIs('visits.prescriptions*')) border-indigo-500 text-indigo-600 dark:text-indigo-400 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            {{ his_trans('workspace.prescriptions') }}
                        </a>

                        <a href="{{ route('visits.spectacles', $visit) }}"
                           class="@if(request()->routeIs('visits.spectacles*')) border-indigo-500 text-indigo-600 dark:text-indigo-400 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            {{ his_trans('workspace.spectacles') }}
                        </a>
                    </nav>
                </div>

                <!-- Workspace Overview Content -->
                <div class="p-6 lg:p-8">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-6">{{ his_trans('workspace.title') }}</h3>

                    <!-- Quick Overview Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Anamnesis Card -->
                        <div class="bg-gray-50 dark:bg-gray-900/20 rounded-lg p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-md font-medium text-gray-900 dark:text-white">{{ his_trans('workspace.anamnesis') }}</h4>
                                <a href="{{ route('visits.anamnesis', $visit) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">
                                    @if($visit->anamnesis) {{ his_trans('edit') }} @else {{ his_trans('add') }} @endif
                                </a>
                            </div>
                            @if($visit->anamnesis)
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ Str::limit($visit->anamnesis->chief_complaint ?? 'Completed', 50) }}</p>
                                <p class="text-xs text-green-600 dark:text-green-400 mt-2">✓ Recorded</p>
                            @else
                                <p class="text-sm text-gray-500 dark:text-gray-400">No anamnesis recorded yet</p>
                            @endif
                        </div>

                        <!-- Examination Card -->
                        <div class="bg-gray-50 dark:bg-gray-900/20 rounded-lg p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-md font-medium text-gray-900 dark:text-white">{{ his_trans('workspace.examination') }}</h4>
                                <a href="{{ route('visits.examination', $visit) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">
                                    @if($visit->ophthalmicExam) {{ his_trans('edit') }} @else {{ his_trans('add') }} @endif
                                </a>
                            </div>
                            @if($visit->ophthalmicExam)
                                <div class="space-y-1">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Visus: {{ $visit->ophthalmicExam->visus_od ?? '—' }} / {{ $visit->ophthalmicExam->visus_os ?? '—' }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">IOP: {{ $visit->ophthalmicExam->iop_od ?? '—' }} / {{ $visit->ophthalmicExam->iop_os ?? '—' }}</p>
                                    <p class="text-xs text-green-600 dark:text-green-400 mt-2">✓ Recorded ({{ $visit->ophthalmicExam->refractions->count() }} refractions)</p>
                                </div>
                            @else
                                <p class="text-sm text-gray-500 dark:text-gray-400">No examination recorded yet</p>
                            @endif
                        </div>

                        <!-- Quick Actions Card -->
                        <div class="bg-gray-50 dark:bg-gray-900/20 rounded-lg p-6">
                            <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">Quick Actions</h4>
                            <div class="space-y-2">
                                <a href="{{ route('visits.prescriptions.create', $visit) }}" class="block text-sm text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">+ Add Prescription</a>
                                <a href="{{ route('visits.imaging.create', $visit) }}" class="block text-sm text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">+ Order Imaging</a>
                                <a href="{{ route('visits.treatments.create', $visit) }}" class="block text-sm text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">+ Add Treatment Plan</a>
                                <a href="{{ route('visits.spectacles.create', $visit) }}" class="block text-sm text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">+ Spectacle Prescription</a>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    @if($visit->imagingStudies->isNotEmpty() || $visit->treatmentPlans->isNotEmpty() || $visit->prescriptions->isNotEmpty())
                        <div class="mt-8">
                            <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">Recent Activity</h4>
                            <div class="space-y-3">
                                @foreach($visit->prescriptions->take(3) as $prescription)
                                    <div class="flex items-center text-sm">
                                        <span class="text-green-600 dark:text-green-400 mr-2">✓</span>
                                        <span class="text-gray-900 dark:text-white">Prescription created with {{ $prescription->prescriptionItems->count() }} medications</span>
                                        <span class="text-gray-500 dark:text-gray-400 ml-auto">{{ $prescription->created_at->diffForHumans() }}</span>
                                    </div>
                                @endforeach

                                @foreach($visit->imagingStudies->take(3) as $study)
                                    <div class="flex items-center text-sm">
                                        <span class="@if($study->status === 'done') text-green-600 dark:text-green-400 @else text-yellow-600 dark:text-yellow-400 @endif mr-2">
                                            @if($study->status === 'done') ✓ @else ○ @endif
                                        </span>
                                        <span class="text-gray-900 dark:text-white">{{ his_trans("imaging_modalities.{$study->modality}") }} {{ his_trans("imaging_eyes.{$study->eye}") }}</span>
                                        <span class="text-gray-500 dark:text-gray-400 ml-auto">{{ $study->created_at->diffForHumans() }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Back Button -->
            <div class="flex justify-between">
                <a href="{{ route('visits.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600">
                    ← {{ his_trans('back') }}
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
