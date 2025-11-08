<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('workspace.imaging') }} - {{ $visit->patient->full_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Workspace Navigation -->
            @include('visits.workspace.partials.navigation', ['visit' => $visit, 'active' => 'imaging'])

            <!-- Imaging Studies -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('imaging.title') }}</h3>
                        <a href="{{ route('visits.imaging.create', $visit) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-600">
                            + {{ __('imaging.add_imaging') }}
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="rounded-md bg-green-50 dark:bg-green-900/20 p-4 mb-6">
                            <div class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}</div>
                        </div>
                    @endif

                    @if($visit->imagingStudies->isNotEmpty())
                        <x-table>
                            <x-slot name="head">
                                <x-table-header>{{__('imaging.studies')}}</x-table-header>
                                <x-table-header-secondary>{{__('imaging.eye')}}</x-table-header-secondary>
                                <x-table-header-secondary>{{__('imaging.status')}}</x-table-header-secondary>
                                <x-table-header-secondary>{{__('imaging.ordered_by')}}</x-table-header-secondary>
                                <x-table-header-secondary>{{__('imaging.date')}}</x-table-header-secondary>
                                <x-table-action-header>{{__('common.table_header.actions')}}</x-table-action-header>
                            </x-slot>

                            <x-slot name="body">
                                @foreach($visit->imagingStudies->sortByDesc('created_at') as $study)
                                    <x-table-row>
                                        <x-table-cell header>
                                            <span class="font-medium">{{ __("imaging.modalities.{$study->modality}") }}</span>
                                        </x-table-cell>

                                        <x-table-cell>
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300">
                                                {{ __("imaging.eyes.{$study->eye}") }}
                                            </span>
                                        </x-table-cell>

                                        <x-table-cell>
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                                @if($study->status === 'ordered') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300
                                                @elseif($study->status === 'done') bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300
                                                @else bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300 @endif">
                                                {{ __("imaging.statuses.{$study->status}") }}
                                            </span>
                                        </x-table-cell>

                                        <x-table-cell>{{ $study->orderedBy->name ?? '—' }}</x-table-cell>

                                        <x-table-cell>
                                            <div class="text-sm">
                                                <div>{{ $study->performed_at?->format('M d, Y') ?? $study->created_at->format('M d, Y') }}</div>
                                                <div class="text-gray-500 dark:text-gray-400">{{ $study->performed_at?->format('g:i A') ?? $study->created_at->format('g:i A') }}</div>
                                            </div>
                                        </x-table-cell>

                                        <x-table-action-cell>
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('visits.imaging.edit', [$visit, $study]) }}"
                                                   class="text-sm font-medium text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                    {{__('common.table_cell.edit')}}
                                                </a>
                                                <form action="{{ route('visits.imaging.destroy', [$visit, $study]) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            onclick="return confirm('Are you sure?')"
                                                            class="text-sm font-medium text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                        {{__('common.table_cell.delete')}}
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
                            title="{{ __('common.table_empty.title') }}"
                            message="{{ __('common.table_empty.message') }}"
                            :actionUrl="route('visits.imaging.create', $visit)"
                            actionText="{{ __('common.table_empty.action_text') }}"
                        />
                    @endif

                    <!-- Back Button -->
                    <div class="flex justify-between mt-8">
                        <a href="{{ route('visits.show', $visit) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600">
                            ← {{ __('common.back') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
