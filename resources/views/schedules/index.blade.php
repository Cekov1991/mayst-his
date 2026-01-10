<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('schedules.title') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="px-4 sm:px-6 lg:px-8">
                <!-- Header Section -->
                <div class="sm:flex sm:items-center">
                    <div class="sm:flex-auto">
                        <h1 class="text-base font-semibold text-gray-900 dark:text-white">{{ __('schedules.title') }}</h1>
                        <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">{{ __('schedules.subtitle') }}</p>
                    </div>
                    <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                        <a href="{{ route('schedules.create') }}"
                           class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 dark:bg-indigo-500 dark:hover:bg-indigo-400 dark:focus-visible:outline-indigo-500">
                            {{ __('schedules.create') }}
                        </a>
                    </div>
                </div>

                <!-- Schedules by Month -->
                <div class="mt-8 space-y-8">
                    @forelse($schedules as $month => $monthSchedules)
                        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                            <div class="px-4 py-5 sm:p-6">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                                    {{ \Carbon\Carbon::parse($month . '-01')->format('F Y') }}
                                </h3>

                                <div class="space-y-4">
                                    @foreach($monthSchedules as $schedule)
                                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                            <div class="flex items-center justify-between">
                                                <div class="flex-1">
                                                    <div class="flex items-center gap-3">
                                                        <h4 class="text-base font-semibold text-gray-900 dark:text-white">
                                                            {{ $schedule->name ?: __('schedules.unnamed_schedule') }}
                                                        </h4>
                                                        @if($schedule->is_active)
                                                            <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-200">
                                                                {{ __('schedules.active') }}
                                                            </span>
                                                        @else
                                                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                                {{ __('schedules.inactive') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                                        <div>
                                                            <span class="font-medium">{{ __('schedules.doctor') }}:</span>
                                                            {{ $schedule->doctor->name }}
                                                        </div>
                                                        <div class="mt-1">
                                                            <span class="font-medium">{{ __('schedules.valid_from') }}:</span>
                                                            {{ $schedule->valid_from->format('M d, Y') }} - {{ $schedule->valid_to->format('M d, Y') }}
                                                        </div>
                                                        <div class="mt-1">
                                                            <span class="font-medium">{{ __('schedules.time_range') }}:</span>
                                                            {{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}
                                                            ({{ __('schedules.slot_interval') }}: {{ $schedule->slot_interval }} {{ __('schedules.minutes') }})
                                                        </div>
                                                        <div class="mt-1">
                                                            <span class="font-medium">{{ __('schedules.slots_count') }}:</span>
                                                            {{ $schedule->slots->count() }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap-2 ml-4">
                                                    <a href="{{ route('schedules.show', $schedule) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                        {{ __('common.view') }}
                                                    </a>
                                                    <a href="{{ route('schedules.edit', $schedule) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                        {{ __('common.edit') }}
                                                    </a>
                                                    <livewire:delete-button
                                                    :model="$schedule"
                                                    route-name="schedules.destroy"
                                                    redirect-route-name="schedules.index"
                                                    :route-params="['schedule' => $schedule]"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                            <div class="px-4 py-5 sm:p-6 text-center">
                                <p class="text-gray-500 dark:text-gray-400">{{ __('schedules.no_schedules') }}</p>
                                <a href="{{ route('schedules.create') }}" class="mt-4 inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-400">
                                    {{ __('schedules.create_first') }}
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Notifications -->
    <x-flash-notifications />
</x-app-layout>

