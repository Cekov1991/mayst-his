<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ his_trans('visits.visit_details') }} - {{ $visit->patient->full_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Visit Overview Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg mb-6">
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
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ his_trans('visits.patient') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                <a href="{{ route('patients.show', $visit->patient) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                    {{ $visit->patient->full_name }}
                                </a>
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ his_trans('visits.doctor') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $visit->doctor->name }}</dd>
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

                        @if($visit->room)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ his_trans('visits.room') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $visit->room }}</dd>
                            </div>
                        @endif

                        @if($visit->arrived_at)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ his_trans('visits.arrived_at') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $visit->arrived_at->format('M d, Y g:i A') }}</dd>
                            </div>
                        @endif

                        @if($visit->started_at)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ his_trans('visits.started_at') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $visit->started_at->format('M d, Y g:i A') }}</dd>
                            </div>
                        @endif

                        @if($visit->completed_at)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ his_trans('visits.completed_at') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $visit->completed_at->format('M d, Y g:i A') }}</dd>
                            </div>
                        @endif
                    </div>

                    @if($visit->reason_for_visit)
                        <div class="mt-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ his_trans('visits.reason_for_visit') }}</dt>
                            <dd class="mt-2 text-sm text-gray-900 dark:text-white whitespace-pre-wrap">{{ $visit->reason_for_visit }}</dd>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Related Information Sections -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Anamnesis -->
                @if($visit->anamnesis)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ his_trans('workspace.anamnesis') }}</h3>
                        </div>
                        <div class="px-6 py-4">
                            <p class="text-sm text-gray-900 dark:text-white">{{ $visit->anamnesis->chief_complaint }}</p>
                        </div>
                    </div>
                @endif

                <!-- Ophthalmic Exam -->
                @if($visit->ophthalmicExam)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ his_trans('workspace.examination') }}</h3>
                        </div>
                        <div class="px-6 py-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Visus OD:</span>
                                    <span class="text-sm text-gray-900 dark:text-white ml-2">{{ $visit->ophthalmicExam->visus_od ?: '—' }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Visus OS:</span>
                                    <span class="text-sm text-gray-900 dark:text-white ml-2">{{ $visit->ophthalmicExam->visus_os ?: '—' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Back Button -->
            <div class="mt-6">
                <a href="{{ route('visits.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600">
                    ← {{ his_trans('back') }}
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
