<div>
    <!-- Queue Stats -->
    <div class="mb-6 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
        <div class="overflow-hidden rounded-lg bg-white dark:bg-gray-800 px-4 py-5 shadow sm:p-6">
            <dt class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('his.visits.queue') }}</dt>
            <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900 dark:text-white">{{ $stats['total'] }}</dd>
        </div>

        <div class="overflow-hidden rounded-lg bg-white dark:bg-gray-800 px-4 py-5 shadow sm:p-6">
            <dt class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('his.visit_status.arrived') }}</dt>
            <dd class="mt-1 text-3xl font-semibold tracking-tight text-yellow-600 dark:text-yellow-400">{{ $stats['arrived'] }}</dd>
        </div>

        <div class="overflow-hidden rounded-lg bg-white dark:bg-gray-800 px-4 py-5 shadow sm:p-6">
            <dt class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('his.visit_status.in_progress') }}</dt>
            <dd class="mt-1 text-3xl font-semibold tracking-tight text-blue-600 dark:text-blue-400">{{ $stats['in_progress'] }}</dd>
        </div>
    </div>

    <!-- Controls -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
        <div class="flex items-center space-x-4">
            <div>
                <label for="selectedDate" class="block text-sm font-medium text-gray-900 dark:text-white">{{__('his.table_header.date')}}</label>
                <input type="date"
                       id="selectedDate"
                       wire:model.live="selectedDate"
                       class="mt-1 block rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:focus:ring-indigo-500">
            </div>

            <div class="pt-6">
                <button type="button"
                        wire:click="refreshQueue"
                        class="inline-flex items-center rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-white/10 dark:text-white dark:ring-white/20 dark:hover:bg-white/20">
                    <svg class="-ml-0.5 mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    {{__('his.clear_button')}}
                </button>
            </div>
        </div>

        <div class="text-sm text-gray-500 dark:text-gray-400">
            <span wire:loading class="inline-flex items-center">
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-indigo-500" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                {{__('his.updating')}}
            </span>
            <span wire:loading.remove>
                {{__('his.last_updated')}}: {{ now()->format('g:i A') }}
            </span>
        </div>
    </div>

    <!-- Queue Table -->
    <x-table>
        <x-slot name="head">
            <x-table-header>{{__('his.table_header.full_name')}}</x-table-header>
            <x-table-header-secondary>{{__('his.table_header.scheduled')}}</x-table-header-secondary>
            <x-table-header-secondary>{{__('his.table_header.status')}}</x-table-header-secondary>
            <x-table-header-secondary>{{__('his.table_header.type')}}</x-table-header-secondary>
            <x-table-header-secondary>{{__('his.table_header.room')}}</x-table-header-secondary>
            <x-table-action-header>{{__('his.table_header.actions')}}</x-table-action-header>
        </x-slot>

        <x-slot name="body">
            @forelse($visits as $visit)
                <x-table-row wire:key="visit-{{ $visit->id }}">
                    <x-table-cell header>
                        <div>
                            <div class="font-medium text-gray-900 dark:text-white">{{ $visit->patient->full_name }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $visit->patient->dob->format('M d, Y') }} ({{ $visit->patient->dob->age }}y)</div>
                        </div>
                    </x-table-cell>

                    <x-table-cell>
                        <div>{{ $visit->scheduled_at->format('g:i A') }}</div>
                        @if($visit->arrived_at)
                            <div class="text-xs text-green-600 dark:text-green-400">
                                Arrived: {{ $visit->arrived_at->format('g:i A') }}
                            </div>
                        @endif
                    </x-table-cell>

                    <x-table-cell>
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset
                            @if($visit->status === 'arrived') bg-yellow-50 text-yellow-800 ring-yellow-600/20 dark:bg-yellow-400/10 dark:text-yellow-400 dark:ring-yellow-400/30
                            @else bg-blue-50 text-blue-700 ring-blue-600/20 dark:bg-blue-400/10 dark:text-blue-400 dark:ring-blue-400/30 @endif">
                            {{ __("his.visit_status.{$visit->status}") }}
                        </span>
                    </x-table-cell>

                    <x-table-cell>
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset
                            @if($visit->type === 'exam') bg-blue-50 text-blue-700 ring-blue-600/20 dark:bg-blue-400/10 dark:text-blue-400 dark:ring-blue-400/30
                            @elseif($visit->type === 'control') bg-green-50 text-green-700 ring-green-600/20 dark:bg-green-400/10 dark:text-green-400 dark:ring-green-400/30
                            @else bg-purple-50 text-purple-700 ring-purple-600/20 dark:bg-purple-400/10 dark:text-purple-400 dark:ring-purple-400/30 @endif">
                            {{ __("his.visit_types.{$visit->type}") }}
                        </span>
                    </x-table-cell>

                    <x-table-cell>
                        {{ $visit->room ?: '—' }}
                    </x-table-cell>

                    <x-table-action-cell>
                        <div class="flex space-x-2">
                            @if($visit->status === 'arrived')
                                <button type="button"
                                        wire:click="startVisit({{ $visit->id }})"
                                        wire:loading.attr="disabled"
                                        class="text-sm font-medium text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 disabled:opacity-50">
                                    {{ __('his.visit_status.start') }}
                                </button>
                            @elseif($visit->status === 'in_progress')
                                <button type="button"
                                        wire:click="completeVisit({{ $visit->id }})"
                                        wire:loading.attr="disabled"
                                        class="text-sm font-medium text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 disabled:opacity-50">
                                    {{ __('his.visit_status.complete') }}
                                </button>
                            @endif

                            <a href="{{ route('visits.show', $visit) }}"
                               class="text-sm font-medium text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300">
                                {{__('his.table_cell.details')}}<span class="sr-only">, {{ $visit->patient->full_name }}</span>
                            </a>
                        </div>
                    </x-table-action-cell>
                </x-table-row>
            @empty
                <x-table-empty
                    colspan="6"
                    title="No visits in queue"
                    message="{{ $this->emptyMessage }}"
                />
            @endforelse
        </x-slot>
    </x-table>

    <!-- Toast Messages -->
    <div x-data="{
        show: false,
        message: '',
        type: 'success'
    }"
         x-on:show-message.window="
            message = $event.detail[0].message;
            type = $event.detail[0].type;
            show = true;
            setTimeout(() => show = false, type === 'error' ? 5000 : 3000);
         ">
        <div x-show="show"
             x-transition
             class="fixed bottom-4 right-4 z-50 rounded-lg px-6 py-4 shadow-lg"
             :class="{
                'bg-green-500 text-white': type === 'success',
                'bg-red-500 text-white': type === 'error'
             }">
            <span x-text="message"></span>
        </div>
    </div>
</div>
