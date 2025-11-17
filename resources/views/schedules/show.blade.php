<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $schedule->name ?: __('schedules.unnamed_schedule') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('schedules.edit', $schedule) }}" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-400">
                    {{ __('common.edit') }}
                </a>
                <form action="{{ route('schedules.destroy', $schedule) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('schedules.confirm_delete') }}')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 dark:bg-red-500 dark:hover:bg-red-400">
                        {{ __('common.delete') }}
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Schedule Details -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg mb-6">
                <div class="p-6 lg:p-8">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('schedules.details') }}</h3>
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('schedules.doctor') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $schedule->doctor->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('schedules.status') }}</dt>
                            <dd class="mt-1">
                                @if($schedule->is_active)
                                    <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-200">
                                        {{ __('schedules.active') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                        {{ __('schedules.inactive') }}
                                    </span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('schedules.valid_from') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $schedule->valid_from->format('M d, Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('schedules.valid_to') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $schedule->valid_to->format('M d, Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('schedules.time_range') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('schedules.slot_interval') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $schedule->slot_interval }} {{ __('schedules.minutes') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('schedules.days_of_week') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                @php
                                    $days = [
                                        0 => __('slots.days.sunday'),
                                        1 => __('slots.days.monday'),
                                        2 => __('slots.days.tuesday'),
                                        3 => __('slots.days.wednesday'),
                                        4 => __('slots.days.thursday'),
                                        5 => __('slots.days.friday'),
                                        6 => __('slots.days.saturday'),
                                    ];
                                @endphp
                                {{ collect($schedule->days_of_week)->map(fn($d) => $days[$d])->join(', ') }}
                            </dd>
                        </div>
                        @if($schedule->week_pattern && count($schedule->week_pattern) > 0)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('schedules.week_pattern') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ collect($schedule->week_pattern)->map(fn($w) => __('schedules.week') . ' ' . $w)->join(', ') }}
                            </dd>
                        </div>
                        @endif
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('schedules.slots_count') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $schedule->slots->count() }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Slots Calendar -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('schedules.slots') }}</h3>
                    @livewire('weekly-slot-calendar', ['doctorId' => $schedule->doctor_id])
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

