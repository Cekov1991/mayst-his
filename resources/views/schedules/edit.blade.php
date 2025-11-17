<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('schedules.edit') }} - {{ $schedule->name ?: __('schedules.unnamed_schedule') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <form action="{{ route('schedules.update', $schedule) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-8">
                            <!-- Doctor Selection -->
                            @if($doctors)
                            <div class="sm:col-span-2">
                                <x-label for="doctor_id" value="{{ __('schedules.doctor') }}" />
                                <select id="doctor_id" name="doctor_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-900 dark:border-gray-600 dark:text-white" required>
                                    <option value="">{{ __('schedules.select_doctor') }}</option>
                                    @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}" {{ old('doctor_id', $schedule->doctor_id) == $doctor->id ? 'selected' : '' }}>
                                            {{ $doctor->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error for="doctor_id" class="mt-2" />
                            </div>
                            @else
                            <input type="hidden" name="doctor_id" value="{{ $schedule->doctor_id }}">
                            @endif

                            <!-- Name -->
                            <div class="sm:col-span-2">
                                <x-label for="name" value="{{ __('schedules.name') }}" />
                                <x-input id="name" type="text" name="name" value="{{ old('name', $schedule->name) }}" class="mt-1 block w-full" placeholder="{{ __('schedules.name_placeholder') }}" />
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('schedules.name_help') }}</p>
                                <x-input-error for="name" class="mt-2" />
                            </div>

                            <!-- Date Range -->
                            <div>
                                <x-label for="valid_from" value="{{ __('schedules.valid_from') }}" />
                                <x-input id="valid_from" type="date" name="valid_from" value="{{ old('valid_from', $schedule->valid_from->format('Y-m-d')) }}" class="mt-1 block w-full" required />
                                <x-input-error for="valid_from" class="mt-2" />
                            </div>

                            <div>
                                <x-label for="valid_to" value="{{ __('schedules.valid_to') }}" />
                                <x-input id="valid_to" type="date" name="valid_to" value="{{ old('valid_to', $schedule->valid_to->format('Y-m-d')) }}" class="mt-1 block w-full" required />
                                <x-input-error for="valid_to" class="mt-2" />
                            </div>

                            <!-- Time Range -->
                            <div>
                                <x-label for="start_time" value="{{ __('schedules.start_time') }}" />
                                <x-input id="start_time" type="time" name="start_time" value="{{ old('start_time', $schedule->start_time->format('H:i')) }}" class="mt-1 block w-full" required />
                                <x-input-error for="start_time" class="mt-2" />
                            </div>

                            <div>
                                <x-label for="end_time" value="{{ __('schedules.end_time') }}" />
                                <x-input id="end_time" type="time" name="end_time" value="{{ old('end_time', $schedule->end_time->format('H:i')) }}" class="mt-1 block w-full" required />
                                <x-input-error for="end_time" class="mt-2" />
                            </div>

                            <!-- Slot Interval -->
                            <div>
                                <x-label for="slot_interval" value="{{ __('schedules.slot_interval') }}" />
                                <select id="slot_interval" name="slot_interval" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-900 dark:border-gray-600 dark:text-white" required>
                                    <option value="15" {{ old('slot_interval', $schedule->slot_interval) == 15 ? 'selected' : '' }}>15 {{ __('schedules.minutes') }}</option>
                                    <option value="30" {{ old('slot_interval', $schedule->slot_interval) == 30 ? 'selected' : '' }}>30 {{ __('schedules.minutes') }}</option>
                                    <option value="45" {{ old('slot_interval', $schedule->slot_interval) == 45 ? 'selected' : '' }}>45 {{ __('schedules.minutes') }}</option>
                                    <option value="60" {{ old('slot_interval', $schedule->slot_interval) == 60 ? 'selected' : '' }}>60 {{ __('schedules.minutes') }}</option>
                                </select>
                                <x-input-error for="slot_interval" class="mt-2" />
                            </div>

                            <!-- Is Active -->
                            <div class="flex items-center">
                                <x-checkbox id="is_active" name="is_active" value="1" :checked="old('is_active', $schedule->is_active)" />
                                <x-label for="is_active" value="{{ __('schedules.is_active') }}" class="ml-2" />
                            </div>

                            <!-- Days of Week -->
                            <div class="sm:col-span-2">
                                <x-label value="{{ __('schedules.days_of_week') }}" />
                                <div class="mt-2 grid grid-cols-2 sm:grid-cols-4 gap-4">
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
                                        $oldDays = old('days_of_week', $schedule->days_of_week ?? [1, 2, 3, 4, 5]);
                                    @endphp
                                    @foreach($days as $dayNum => $dayName)
                                        <label class="flex items-center">
                                            <input type="checkbox"
                                                   name="days_of_week[]"
                                                   value="{{ $dayNum }}"
                                                   {{ in_array($dayNum, $oldDays) ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $dayName }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <x-input-error for="days_of_week" class="mt-2" />
                            </div>

                            <!-- Week Pattern (Optional) -->
                            <div class="sm:col-span-2">
                                <x-label value="{{ __('schedules.week_pattern') }}" />
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('schedules.week_pattern_help') }}</p>
                                <div class="mt-2 grid grid-cols-5 gap-4">
                                    @php
                                        $oldWeekPattern = old('week_pattern', $schedule->week_pattern ?? []);
                                    @endphp
                                    @for($i = 1; $i <= 5; $i++)
                                        <label class="flex items-center">
                                            <input type="checkbox"
                                                   name="week_pattern[]"
                                                   value="{{ $i }}"
                                                   {{ in_array($i, $oldWeekPattern) ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ __('schedules.week') }} {{ $i }}</span>
                                        </label>
                                    @endfor
                                </div>
                                <x-input-error for="week_pattern" class="mt-2" />
                            </div>

                            <!-- Specific Dates (Optional) -->
                            <div class="sm:col-span-2">
                                <x-label for="specific_dates" value="{{ __('schedules.specific_dates') }}" />
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('schedules.specific_dates_help') }}</p>
                                <div id="specific-dates-container" class="mt-2 space-y-2">
                                    @php
                                        $oldSpecificDates = old('specific_dates', $schedule->specific_dates ?? []);
                                    @endphp
                                    @if(count($oldSpecificDates) > 0)
                                        @foreach($oldSpecificDates as $date)
                                            <div class="flex gap-2">
                                                <x-input type="date" name="specific_dates[]" value="{{ $date }}" class="flex-1" />
                                                <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-200 dark:bg-red-900 text-red-700 dark:text-red-300 rounded-md hover:bg-red-300 dark:hover:bg-red-800">
                                                    {{ __('common.delete') }}
                                                </button>
                                            </div>
                                        @endforeach
                                    @endif
                                    <div class="flex gap-2">
                                        <button type="button" onclick="addSpecificDate()" class="px-3 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">
                                            {{ __('schedules.add_date') }}
                                        </button>
                                    </div>
                                </div>
                                <x-input-error for="specific_dates" class="mt-2" />
                            </div>

                            <!-- Excluded Dates (Optional) -->
                            <div class="sm:col-span-2">
                                <x-label for="excluded_dates" value="{{ __('schedules.excluded_dates') }}" />
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('schedules.excluded_dates_help') }}</p>
                                <div id="excluded-dates-container" class="mt-2 space-y-2">
                                    @php
                                        $oldExcludedDates = old('excluded_dates', $schedule->excluded_dates ?? []);
                                    @endphp
                                    @if(count($oldExcludedDates) > 0)
                                        @foreach($oldExcludedDates as $date)
                                            <div class="flex gap-2">
                                                <x-input type="date" name="excluded_dates[]" value="{{ $date }}" class="flex-1" />
                                                <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-200 dark:bg-red-900 text-red-700 dark:text-red-300 rounded-md hover:bg-red-300 dark:hover:bg-red-800">
                                                    {{ __('common.delete') }}
                                                </button>
                                            </div>
                                        @endforeach
                                    @endif
                                    <div class="flex gap-2">
                                        <button type="button" onclick="addExcludedDate()" class="px-3 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">
                                            {{ __('schedules.add_date') }}
                                        </button>
                                    </div>
                                </div>
                                <x-input-error for="excluded_dates" class="mt-2" />
                            </div>

                            <!-- Regenerate Slots -->
                            <div class="sm:col-span-2">
                                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                                    <div class="flex items-center">
                                        <x-checkbox id="regenerate_slots" name="regenerate_slots" value="1" :checked="old('regenerate_slots', false)" />
                                        <x-label for="regenerate_slots" value="{{ __('schedules.regenerate_slots') }}" class="ml-2" />
                                    </div>
                                    <p class="mt-2 text-sm text-yellow-800 dark:text-yellow-200">{{ __('schedules.regenerate_slots_help') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end mt-6 space-x-3">
                            <a href="{{ route('schedules.index') }}" class="inline-flex justify-center rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600">
                                {{ __('common.cancel') }}
                            </a>
                            <x-button type="submit">
                                {{ __('common.save') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function addSpecificDate() {
            const container = document.getElementById('specific-dates-container');
            const div = document.createElement('div');
            div.className = 'flex gap-2';
            const deleteText = @json(__('common.delete'));
            div.innerHTML = `
                <input type="date" name="specific_dates[]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-600 dark:text-white" />
                <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-200 dark:bg-red-900 text-red-700 dark:text-red-300 rounded-md hover:bg-red-300 dark:hover:bg-red-800">
                    ${deleteText}
                </button>
            `;
            container.appendChild(div);
        }

        function addExcludedDate() {
            const container = document.getElementById('excluded-dates-container');
            const div = document.createElement('div');
            div.className = 'flex gap-2';
            const deleteText = @json(__('common.delete'));
            div.innerHTML = `
                <input type="date" name="excluded_dates[]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-600 dark:text-white" />
                <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-200 dark:bg-red-900 text-red-700 dark:text-red-300 rounded-md hover:bg-red-300 dark:hover:bg-red-800">
                    ${deleteText}
                </button>
            `;
            container.appendChild(div);
        }
    </script>
</x-app-layout>

