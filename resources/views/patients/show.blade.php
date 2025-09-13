<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $patient->full_name }} - {{ his_trans('patients.patient_details') }}
            </h2>
            <div class="flex items-center space-x-4">
                <x-locale-switcher />
                <a href="{{ route('patients.edit', $patient) }}"
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    {{ his_trans('edit') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Patient Information Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                            {{ his_trans('patients.patient_details') }}
                        </h3>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            {{ his_trans('patients.created_at') }}: {{ $patient->created_at->format('d.m.Y H:i') }}
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ his_trans('patients.first_name') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $patient->first_name }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ his_trans('patients.last_name') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $patient->last_name }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ his_trans('patients.sex') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ his_trans("sex_options.{$patient->sex}") }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ his_trans('patients.dob') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $patient->dob->format('d.m.Y') }}
                                <span class="text-gray-500 dark:text-gray-400">({{ his_trans('patients.age') }}: {{ $patient->dob->age }})</span>
                            </dd>
                        </div>

                        @if($patient->phone)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ his_trans('patients.phone') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                <a href="tel:{{ $patient->phone }}" class="text-blue-600 hover:text-blue-500">{{ $patient->phone }}</a>
                            </dd>
                        </div>
                        @endif

                        @if($patient->email)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ his_trans('patients.email') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                <a href="mailto:{{ $patient->email }}" class="text-blue-600 hover:text-blue-500">{{ $patient->email }}</a>
                            </dd>
                        </div>
                        @endif

                        @if($patient->unique_master_citizen_number)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ his_trans('patients.unique_master_citizen_number') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $patient->unique_master_citizen_number }}</dd>
                        </div>
                        @endif

                        @if($patient->address)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ his_trans('patients.address') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $patient->address }}
                                @if($patient->city || $patient->country)
                                    <br>
                                    {{ $patient->city }}@if($patient->city && $patient->country), @endif{{ $patient->country }}
                                @endif
                            </dd>
                        </div>
                        @endif

                        @if($patient->notes)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ his_trans('patients.notes') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $patient->notes }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="px-6 py-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100 mb-4">
                        Quick Actions
                    </h3>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('visits.create', ['patient_id' => $patient->id]) }}"
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            {{ his_trans('visits.add_visit') }}
                        </a>

                        <a href="{{ route('patients.edit', $patient) }}"
                           class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500 dark:hover:bg-gray-700">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                            {{ his_trans('patients.edit_patient') }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Visits -->
            @if($patient->visits->count() > 0)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                            {{ his_trans('visits.title') }}
                        </h3>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            {{ his_trans('patients.visits_count') }}: {{ $patient->visits->count() }}
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    {{ his_trans('visits.scheduled_at') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    {{ his_trans('visits.doctor') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    {{ his_trans('visits.type') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    {{ his_trans('visits.status') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    {{ his_trans('visits.reason_for_visit') }}
                                </th>
                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">{{ his_trans('actions') }}</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($patient->visits as $visit)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $visit->scheduled_at->format('d.m.Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $visit->doctor->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ his_trans("visit_types.{$visit->type}") }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                            @if($visit->status === 'completed') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                            @elseif($visit->status === 'in_progress') bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100
                                            @elseif($visit->status === 'arrived') bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100
                                            @elseif($visit->status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                                            @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200 @endif">
                                            {{ his_trans("visit_status.{$visit->status}") }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300 max-w-xs truncate">
                                        {{ $visit->reason_for_visit }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('visits.show', $visit) }}"
                                           class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                            {{ his_trans('view') }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @else
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="px-6 py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No visits yet</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by scheduling the first visit for this patient.</p>
                    <div class="mt-6">
                        <a href="{{ route('visits.create', ['patient_id' => $patient->id]) }}"
                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            {{ his_trans('visits.add_visit') }}
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div x-data="{ show: true }"
             x-show="show"
             x-transition
             x-init="setTimeout(() => show = false, 3000)"
             class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div x-data="{ show: true }"
             x-show="show"
             x-transition
             x-init="setTimeout(() => show = false, 5000)"
             class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg">
            {{ session('error') }}
        </div>
    @endif
</x-app-layout>
