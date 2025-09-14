<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ his_trans('workspace.treatment') }} - {{ $visit->patient->full_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Workspace Navigation -->
            @include('visits.workspace.partials.navigation', ['visit' => $visit, 'active' => 'treatments'])

            <!-- Treatment Plans -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ his_trans('treatment.plans') }}</h3>
                        <a href="{{ route('visits.treatments.create', $visit) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-600">
                            + {{ his_trans('treatment.add_plan') }}
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="rounded-md bg-green-50 dark:bg-green-900/20 p-4 mb-6">
                            <div class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}</div>
                        </div>
                    @endif

                    @if($visit->treatmentPlans->isNotEmpty())
                        <x-table>
                            <x-slot name="head">
                                <x-table-header>Plan Type</x-table-header>
                                <x-table-header-secondary>Recommendation</x-table-header-secondary>
                                <x-table-header-secondary>Status</x-table-header-secondary>
                                <x-table-header-secondary>Planned Date</x-table-header-secondary>
                                <x-table-header-secondary>Created</x-table-header-secondary>
                                <x-table-action-header>Actions</x-table-action-header>
                            </x-slot>

                            <x-slot name="body">
                                @foreach($visit->treatmentPlans->sortByDesc('created_at') as $plan)
                                    <x-table-row>
                                        <x-table-cell header>
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                                @if($plan->plan_type === 'surgery') bg-purple-100 text-purple-800 dark:bg-purple-900/20 dark:text-purple-300
                                                @elseif($plan->plan_type === 'injection') bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300
                                                @elseif($plan->plan_type === 'medical') bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300
                                                @else bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-300 @endif">
                                                {{ his_trans("treatment_types.{$plan->plan_type}") }}
                                            </span>
                                        </x-table-cell>

                                        <x-table-cell>
                                            <div class="max-w-xs">
                                                <div class="font-medium text-gray-900 dark:text-white">{{ $plan->recommendation }}</div>
                                                @if($plan->details)
                                                    <div class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ Str::limit($plan->details, 60) }}</div>
                                                @endif
                                            </div>
                                        </x-table-cell>

                                        <x-table-cell>
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                                @if($plan->status === 'proposed') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300
                                                @elseif($plan->status === 'accepted') bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300
                                                @elseif($plan->status === 'scheduled') bg-indigo-100 text-indigo-800 dark:bg-indigo-900/20 dark:text-indigo-300
                                                @elseif($plan->status === 'done') bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300
                                                @else bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300 @endif">
                                                {{ his_trans("treatment_status.{$plan->status}") }}
                                            </span>
                                        </x-table-cell>

                                        <x-table-cell>
                                            {{ $plan->planned_date?->format('M d, Y') ?? '—' }}
                                        </x-table-cell>

                                        <x-table-cell>
                                            <div class="text-sm">
                                                <div>{{ $plan->created_at->format('M d, Y') }}</div>
                                                <div class="text-gray-500 dark:text-gray-400">{{ $plan->created_at->format('g:i A') }}</div>
                                            </div>
                                        </x-table-cell>

                                        <x-table-action-cell>
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('visits.treatments.edit', [$visit, $plan]) }}"
                                                   class="text-sm font-medium text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                    Edit
                                                </a>
                                                <form action="{{ route('visits.treatments.destroy', [$visit, $plan]) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            onclick="return confirm('Are you sure?')"
                                                            class="text-sm font-medium text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </x-table-action-cell>
                                    </x-table-row>
                                @endforeach
                            </x-slot>
                        </x-table>
                    @else
                        <x-table-empty
                            title="No treatment plans created"
                            message="Create treatment plans and recommendations for this visit."
                            :actionUrl="route('visits.treatments.create', $visit)"
                            actionText="Create First Plan"
                        />
                    @endif

                    <!-- Back Button -->
                    <div class="flex justify-between mt-8">
                        <a href="{{ route('visits.show', $visit) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600">
                            ← {{ his_trans('back') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
