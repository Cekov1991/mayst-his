<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('slots.create_slots') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <form action="{{ route('slots.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-8">
                            <!-- Doctor Selection -->
                            @if($doctors)
                            <div class="sm:col-span-2">
                                <x-label for="doctor_id" value="{{ __('slots.doctor') }}" />
                                <select id="doctor_id" name="doctor_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-900 dark:border-gray-600 dark:text-white" required>
                                    <option value="">{{ __('slots.select_doctor') }}</option>
                                    @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                            {{ $doctor->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error for="doctor_id" class="mt-2" />
                            </div>
                            @else
                            <input type="hidden" name="doctor_id" value="{{ auth()->user()->id }}">
                            @endif

                            <!-- Date Range -->
                            <div>
                                <x-label for="date_from" value="{{ __('slots.date_from') }}" />
                                <x-input id="date_from" type="date" name="date_from" value="{{ old('date_from', today()->format('Y-m-d')) }}" class="mt-1 block w-full" required />
                                <x-input-error for="date_from" class="mt-2" />
                            </div>

                            <div>
                                <x-label for="date_to" value="{{ __('slots.date_to') }}" />
                                <x-input id="date_to" type="date" name="date_to" value="{{ old('date_to', today()->addMonths(3)->format('Y-m-d')) }}" class="mt-1 block w-full" required />
                                <x-input-error for="date_to" class="mt-2" />
                            </div>

                            <!-- Time Range -->
                            <div>
                                <x-label for="time_from" value="{{ __('slots.time_from') }}" />
                                <x-input id="time_from" type="time" name="time_from" value="{{ old('time_from', '09:00') }}" class="mt-1 block w-full" required />
                                <x-input-error for="time_from" class="mt-2" />
                            </div>

                            <div>
                                <x-label for="time_to" value="{{ __('slots.time_to') }}" />
                                <x-input id="time_to" type="time" name="time_to" value="{{ old('time_to', '17:00') }}" class="mt-1 block w-full" required />
                                <x-input-error for="time_to" class="mt-2" />
                            </div>

                            <!-- Days of Week -->
                            <div class="sm:col-span-2">
                                <x-label value="{{ __('slots.days_of_week') }}" />
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
                                        $oldDays = old('days_of_week', [1, 2, 3, 4, 5]); // Default to Monday-Friday
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
                        </div>

                        <div class="flex justify-end mt-6 space-x-3">
                            <a href="{{ route('slots.index') }}" class="inline-flex justify-center rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600">
                                {{ __('cancel') }}
                            </a>
                            <x-button type="submit">
                                {{ __('slots.create_slots') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

