<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('visits.edit_visit') }} - {{ $visit->patient->full_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @can('delete', $visit)
                <div class="flex justify-end mb-4">
                    <form action="{{ route('visits.destroy', $visit) }}" method="POST"
                        onsubmit="return confirm('{{ __('confirm_delete') }}');" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-red-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:bg-red-500 dark:hover:bg-red-600">
                            {{ __('delete') }}
                        </button>
                    </form>
                </div>
            @endcan
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <form action="{{ route('visits.update', $visit) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-8">

                            <!-- Doctor Selection -->
                            @can('edit-visits-for-other-doctors')
                            <div>
                                <x-label for="doctor_id" value="{{ __('visits.doctor') }}" />
                                <select id="doctor_id" name="doctor_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-900 dark:border-gray-600 dark:text-white" required>
                                    <option value="">Select Doctor</option>
                                    @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}" {{ old('doctor_id', $visit->doctor_id) == $doctor->id ? 'selected' : '' }}>
                                            {{ $doctor->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error for="doctor_id" class="mt-2" />
                            </div>
                            @else
                            <input type="hidden" name="doctor_id" value="{{ $visit->doctor_id }}" id="doctor_id">
                            @endcan

                            <!-- Visit Type -->
                            <div>
                                <x-label for="type" value="{{ __('visits.type') }}" />
                                <select id="type" name="type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-900 dark:border-gray-600 dark:text-white" required>
                                    <option value="">Select Type</option>
                                    @foreach(['exam', 'control', 'surgery'] as $typeOption)
                                        <option value="{{ $typeOption }}" {{ old('type', $visit->type) === $typeOption ? 'selected' : '' }}>
                                            {{ __("visits.types.{$typeOption}") }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error for="type" class="mt-2" />
                            </div>

                            <!-- Status -->
                            <div>
                                <x-label for="status" value="{{ __('visits.status') }}" />
                                <select id="status" name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-900 dark:border-gray-600 dark:text-white" required>
                                    @foreach(['scheduled', 'arrived', 'in_progress', 'completed', 'cancelled'] as $statusOption)
                                        <option value="{{ $statusOption }}" {{ old('status', $visit->status) === $statusOption ? 'selected' : '' }}>
                                            {{ __("visits.statuses.{$statusOption}") }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error for="status" class="mt-2" />
                            </div>

                            <!-- Slot Selection -->
                            <div>
                                @livewire('available-slot-selector', [
                                    'doctorId' => $visit->doctor_id,
                                    'selectedDate' => $visit->scheduled_at ? \Carbon\Carbon::parse($visit->scheduled_at)->format('Y-m-d') : null,
                                    'selectedSlotId' => $visit->slot_id,
                                    'label' => __('visits.scheduled_at')
                                ])
                                <x-input-error for="slot_id" class="mt-2" />
                                <x-input-error for="scheduled_at" class="mt-2" />
                            </div>

                            <!-- Room -->
                            <div>
                                <x-label for="room" value="{{ __('visits.room') }}" />
                                <x-input id="room" type="text" name="room" value="{{ old('room', $visit->room) }}"
                                         placeholder="e.g., Room 101" class="mt-1 block w-full" />
                                <x-input-error for="room" class="mt-2" />
                            </div>

                            <!-- Reason for Visit -->
                            <div class="sm:col-span-2">
                                <x-label for="reason_for_visit" value="{{ __('visits.reason_for_visit') }}" />
                                <textarea id="reason_for_visit" name="reason_for_visit" rows="4"
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-900 dark:border-gray-600 dark:text-white"
                                          placeholder="Describe the reason for this visit..." required>{{ old('reason_for_visit', $visit->reason_for_visit) }}</textarea>
                                <x-input-error for="reason_for_visit" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex justify-end mt-6">
                            <!-- Action Buttons -->
                            <div class="flex space-x-3">
                                <a href="{{ route('visits.show', $visit) }}" class="inline-flex justify-center rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600">
                                    {{ __('cancel') }}
                                </a>
                                <x-button type="submit">
                                    {{ __('common.save') }}
                                </x-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const doctorSelect = document.getElementById('doctor_id');

            if (doctorSelect) {
                doctorSelect.addEventListener('change', function() {
                    Livewire.dispatch('doctorChanged', {"doctorId": this.value});
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
