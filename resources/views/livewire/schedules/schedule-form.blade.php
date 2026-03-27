<div>
    <form wire:submit="save">
        <div class="grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-8">
            <!-- Doctor Selection -->
            @if($doctors)
            <div class="sm:col-span-2">
                <x-label for="doctor_id" value="{{ __('schedules.doctor') }}" />
                <select id="doctor_id" wire:model="doctorId" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-900 dark:border-gray-600 dark:text-white" required>
                    <option value="">{{ __('schedules.select_doctor') }}</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor['id'] }}">{{ $doctor['name'] }}</option>
                    @endforeach
                </select>
                @error('doctorId')
                    <x-input-error for="doctorId" class="mt-2" />
                @enderror
            </div>
            @else
            <input type="hidden" wire:model="doctorId">
            @endif

            <!-- Name -->
            <div class="sm:col-span-2">
                <x-label for="name" value="{{ __('schedules.name') }}" />
                <x-input id="name" type="text" wire:model="name" class="mt-1 block w-full" placeholder="{{ __('schedules.name_placeholder') }}" />
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('schedules.name_help') }}</p>
                @error('name')
                    <x-input-error for="name" class="mt-2" />
                @enderror
            </div>

            <!-- Date Range -->
            <div>
                <x-label for="valid_from" value="{{ __('schedules.valid_from') }}" />
                <x-input id="valid_from" type="date" wire:model="validFrom" class="mt-1 block w-full" required />
                @error('validFrom')
                    <x-input-error for="validFrom" class="mt-2" />
                @enderror
            </div>

            <div>
                <x-label for="valid_to" value="{{ __('schedules.valid_to') }}" />
                <x-input id="valid_to" type="date" wire:model="validTo" class="mt-1 block w-full" required />
                @error('validTo')
                    <x-input-error for="validTo" class="mt-2" />
                @enderror
            </div>

            <!-- Time Range -->
            <div>
                <x-label for="start_time" value="{{ __('schedules.start_time') }}" />
                <x-input id="start_time" type="time" wire:model="startTime" class="mt-1 block w-full" required />
                @error('startTime')
                    <x-input-error for="startTime" class="mt-2" />
                @enderror
            </div>

            <div>
                <x-label for="end_time" value="{{ __('schedules.end_time') }}" />
                <x-input id="end_time" type="time" wire:model="endTime" class="mt-1 block w-full" required />
                @error('endTime')
                    <x-input-error for="endTime" class="mt-2" />
                @enderror
            </div>

            <!-- Slot Interval -->
            <div>
                <x-label for="slot_interval" value="{{ __('schedules.slot_interval') }}" />
                <select id="slot_interval" wire:model="slotInterval" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-900 dark:border-gray-600 dark:text-white" required>
                    <option value="15">15 {{ __('schedules.minutes') }}</option>
                    <option value="30">30 {{ __('schedules.minutes') }}</option>
                    <option value="45">45 {{ __('schedules.minutes') }}</option>
                    <option value="60">60 {{ __('schedules.minutes') }}</option>
                </select>
                @error('slotInterval')
                    <x-input-error for="slotInterval" class="mt-2" />
                @enderror
            </div>

            <!-- Is Active -->
            <div class="flex items-center">
                <x-checkbox id="is_active" wire:model="isActive" />
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
                    @endphp
                    @foreach($days as $dayNum => $dayName)
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox"
                                   wire:click="toggleDayOfWeek({{ $dayNum }})"
                                   @checked(in_array($dayNum, $daysOfWeek))
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $dayName }}</span>
                        </label>
                    @endforeach
                </div>
                @error('daysOfWeek')
                    <x-input-error for="daysOfWeek" class="mt-2" />
                @enderror
            </div>

            <!-- Week Pattern (Optional) -->
            <div class="sm:col-span-2">
                <x-label value="{{ __('schedules.week_pattern') }}" />
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('schedules.week_pattern_help') }}</p>
                <div class="mt-2 grid grid-cols-5 gap-4">
                    @for($i = 1; $i <= 5; $i++)
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox"
                                   wire:click="toggleWeekPattern({{ $i }})"
                                   @checked(in_array($i, $weekPattern))
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ __('schedules.week') }} {{ $i }}</span>
                        </label>
                    @endfor
                </div>
                @error('weekPattern')
                    <x-input-error for="weekPattern" class="mt-2" />
                @enderror
            </div>

            <!-- Specific Dates (Optional) -->
            <div class="sm:col-span-2">
                <x-label for="specific_dates" value="{{ __('schedules.specific_dates') }}" />
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('schedules.specific_dates_help') }}</p>
                <div class="mt-2 space-y-2">
                    @foreach($specificDates as $index => $date)
                        <div class="flex gap-2" wire:key="specific-date-{{ $index }}">
                            <x-input type="date" wire:model="specificDates.{{ $index }}" class="flex-1" />
                            <button type="button" wire:click="removeSpecificDate({{ $index }})" wire:loading.attr="disabled"
                                    class="px-3 py-2 bg-red-200 dark:bg-red-900 text-red-700 dark:text-red-300 rounded-md hover:bg-red-300 dark:hover:bg-red-800">
                                {{ __('common.delete') }}
                            </button>
                        </div>
                    @endforeach
                    <div class="flex gap-2">
                        <button type="button" wire:click="addSpecificDate" wire:loading.attr="disabled"
                                class="px-3 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">
                            {{ __('schedules.add_date') }}
                        </button>
                    </div>
                </div>
                @error('specificDates')
                    <x-input-error for="specificDates" class="mt-2" />
                @enderror
            </div>

            <!-- Excluded Dates (Optional) -->
            <div class="sm:col-span-2">
                <x-label for="excluded_dates" value="{{ __('schedules.excluded_dates') }}" />
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('schedules.excluded_dates_help') }}</p>
                <div class="mt-2 space-y-2">
                    @foreach($excludedDates as $index => $date)
                        <div class="flex gap-2" wire:key="excluded-date-{{ $index }}">
                            <x-input type="date" wire:model="excludedDates.{{ $index }}" class="flex-1" />
                            <button type="button" wire:click="removeExcludedDate({{ $index }})" wire:loading.attr="disabled"
                                    class="px-3 py-2 bg-red-200 dark:bg-red-900 text-red-700 dark:text-red-300 rounded-md hover:bg-red-300 dark:hover:bg-red-800">
                                {{ __('common.delete') }}
                            </button>
                        </div>
                    @endforeach
                    <div class="flex gap-2">
                        <button type="button" wire:click="addExcludedDate" wire:loading.attr="disabled"
                                class="px-3 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">
                            {{ __('schedules.add_date') }}
                        </button>
                    </div>
                </div>
                @error('excludedDates')
                    <x-input-error for="excludedDates" class="mt-2" />
                @enderror
            </div>

            <!-- Regenerate Slots (Edit Mode Only) -->
            @if($schedule)
            <div class="sm:col-span-2">
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                    <div class="flex items-center">
                        <x-checkbox id="regenerate_slots" wire:model="regenerateSlots" />
                        <x-label for="regenerate_slots" value="{{ __('schedules.regenerate_slots') }}" class="ml-2" />
                    </div>
                    <p class="mt-2 text-sm text-yellow-800 dark:text-yellow-200">{{ __('schedules.regenerate_slots_help') }}</p>
                </div>
            </div>
            @endif
        </div>

        <div class="flex justify-end mt-6 space-x-3">
            <a href="{{ route('schedules.index') }}" class="inline-flex justify-center rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600">
                {{ __('common.cancel') }}
            </a>
            <x-button type="submit" wire:loading.attr="disabled" class="disabled:opacity-50">
                <span wire:loading.remove wire:target="save">{{ $schedule->exists ? __('common.save') : __('schedules.create') }}</span>
                <span wire:loading wire:target="save">{{ __('common.processing') }}...</span>
            </x-button>
        </div>
    </form>
</div>
