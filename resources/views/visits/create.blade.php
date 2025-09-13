<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ his_trans('visits.add_visit') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <form action="{{ route('visits.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-8">
                            <!-- Patient Selection -->
                            <div class="sm:col-span-2">
                                <x-label for="patient_id" value="{{ his_trans('visits.patient') }}" />
                                <select id="patient_id" name="patient_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-900 dark:border-gray-600 dark:text-white" required>
                                    <option value="">{{ his_trans('visits.patient') }}</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}" {{ old('patient_id', $selectedPatientId) == $patient->id ? 'selected' : '' }}>
                                            {{ $patient->full_name }} - {{ $patient->dob->format('M d, Y') }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error for="patient_id" class="mt-2" />
                            </div>

                            <!-- Doctor Selection -->
                            <div>
                                <x-label for="doctor_id" value="{{ his_trans('visits.doctor') }}" />
                                <select id="doctor_id" name="doctor_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-900 dark:border-gray-600 dark:text-white" required>
                                    <option value="">Select Doctor</option>
                                    @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                            {{ $doctor->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error for="doctor_id" class="mt-2" />
                            </div>

                            <!-- Visit Type -->
                            <div>
                                <x-label for="type" value="{{ his_trans('visits.type') }}" />
                                <select id="type" name="type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-900 dark:border-gray-600 dark:text-white" required>
                                    <option value="">Select Type</option>
                                    @foreach(['exam', 'control', 'surgery'] as $typeOption)
                                        <option value="{{ $typeOption }}" {{ old('type') === $typeOption ? 'selected' : '' }}>
                                            {{ his_trans("visit_types.{$typeOption}") }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error for="type" class="mt-2" />
                            </div>

                            <!-- Status -->
                            <div>
                                <x-label for="status" value="{{ his_trans('visits.status') }}" />
                                <select id="status" name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-900 dark:border-gray-600 dark:text-white" required>
                                    @foreach(['scheduled', 'arrived', 'in_progress', 'completed'] as $statusOption)
                                        <option value="{{ $statusOption }}" {{ old('status', 'scheduled') === $statusOption ? 'selected' : '' }}>
                                            {{ his_trans("visit_status.{$statusOption}") }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error for="status" class="mt-2" />
                            </div>

                            <!-- Scheduled Date & Time -->
                            <div>
                                <x-label for="scheduled_at" value="{{ his_trans('visits.scheduled_at') }}" />
                                <x-input id="scheduled_at" type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at', now()->format('Y-m-d\TH:i')) }}" class="mt-1 block w-full" required />
                                <x-input-error for="scheduled_at" class="mt-2" />
                            </div>

                            <!-- Room -->
                            <div>
                                <x-label for="room" value="{{ his_trans('visits.room') }}" />
                                <x-input id="room" type="text" name="room" value="{{ old('room') }}" placeholder="e.g., Room 101" class="mt-1 block w-full" />
                                <x-input-error for="room" class="mt-2" />
                            </div>

                            <!-- Reason for Visit -->
                            <div class="sm:col-span-2">
                                <x-label for="reason_for_visit" value="{{ his_trans('visits.reason_for_visit') }}" />
                                <textarea id="reason_for_visit" name="reason_for_visit" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-900 dark:border-gray-600 dark:text-white" placeholder="Describe the reason for this visit..." required>{{ old('reason_for_visit') }}</textarea>
                                <x-input-error for="reason_for_visit" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex justify-end mt-6 space-x-3">
                            <a href="{{ route('visits.index') }}" class="inline-flex justify-center rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600">
                                {{ his_trans('cancel') }}
                            </a>
                            <x-button type="submit">
                                {{ his_trans('visits.add_visit') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
