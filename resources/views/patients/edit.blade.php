<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ his_trans('patients.edit_patient') }} - {{ $patient->full_name }}
            </h2>
            <x-locale-switcher />
        </div>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('patients.update', $patient) }}">
                @csrf
                @method('PUT')

                <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                        <div class="px-4 sm:px-0">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ his_trans('patients.patient_details') }}
                            </h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Update the patient's personal information.
                            </p>
                        </div>
                    </div>

                    <div class="mt-5 md:mt-0 md:col-span-2">
                        <div class="px-4 py-5 bg-white dark:bg-gray-800 sm:p-6 shadow sm:rounded-md">
                            <div class="grid grid-cols-6 gap-6">

                                <!-- First Name -->
                                <div class="col-span-6 sm:col-span-3">
                                    <x-label for="first_name" value="{{ his_trans('patients.first_name') }}" />
                                    <x-input id="first_name" name="first_name" type="text" class="mt-1 block w-full" :value="old('first_name', $patient->first_name)" required autofocus />
                                    <x-input-error for="first_name" class="mt-2" />
                                </div>

                                <!-- Last Name -->
                                <div class="col-span-6 sm:col-span-3">
                                    <x-label for="last_name" value="{{ his_trans('patients.last_name') }}" />
                                    <x-input id="last_name" name="last_name" type="text" class="mt-1 block w-full" :value="old('last_name', $patient->last_name)" required />
                                    <x-input-error for="last_name" class="mt-2" />
                                </div>

                                <!-- Sex -->
                                <div class="col-span-6 sm:col-span-3">
                                    <x-label for="sex" value="{{ his_trans('patients.sex') }}" />
                                    <select id="sex" name="sex" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                        <option value="">{{ his_trans('patients.sex') }}...</option>
                                        @foreach(['male', 'female', 'other', 'unknown'] as $sexOption)
                                            <option value="{{ $sexOption }}" {{ old('sex', $patient->sex) === $sexOption ? 'selected' : '' }}>
                                                {{ his_trans("sex_options.{$sexOption}") }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error for="sex" class="mt-2" />
                                </div>

                                <!-- Date of Birth -->
                                <div class="col-span-6 sm:col-span-3">
                                    <x-label for="dob" value="{{ his_trans('patients.dob') }}" />
                                    <x-input id="dob" name="dob" type="date" class="mt-1 block w-full" :value="old('dob', $patient->dob->format('Y-m-d'))" max="{{ date('Y-m-d') }}" required />
                                    <x-input-error for="dob" class="mt-2" />
                                </div>

                                <!-- Phone -->
                                <div class="col-span-6 sm:col-span-3">
                                    <x-label for="phone" value="{{ his_trans('patients.phone') }}" />
                                    <x-input id="phone" name="phone" type="tel" class="mt-1 block w-full" :value="old('phone', $patient->phone)" />
                                    <x-input-error for="phone" class="mt-2" />
                                </div>

                                <!-- Email -->
                                <div class="col-span-6 sm:col-span-3">
                                    <x-label for="email" value="{{ his_trans('patients.email') }}" />
                                    <x-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $patient->email)" />
                                    <x-input-error for="email" class="mt-2" />
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <x-section-border />

                <!-- Address Information -->
                <div class="mt-10 sm:mt-0">
                    <div class="md:grid md:grid-cols-3 md:gap-6">
                        <div class="md:col-span-1">
                            <div class="px-4 sm:px-0">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    Address Information
                                </h3>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    Patient's address and location information.
                                </p>
                            </div>
                        </div>

                        <div class="mt-5 md:mt-0 md:col-span-2">
                            <div class="px-4 py-5 bg-white dark:bg-gray-800 sm:p-6 shadow sm:rounded-md">
                                <div class="grid grid-cols-6 gap-6">

                                    <!-- Address -->
                                    <div class="col-span-6">
                                        <x-label for="address" value="{{ his_trans('patients.address') }}" />
                                        <textarea id="address" name="address" rows="2" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('address', $patient->address) }}</textarea>
                                        <x-input-error for="address" class="mt-2" />
                                    </div>

                                    <!-- City -->
                                    <div class="col-span-6 sm:col-span-3">
                                        <x-label for="city" value="{{ his_trans('patients.city') }}" />
                                        <x-input id="city" name="city" type="text" class="mt-1 block w-full" :value="old('city', $patient->city)" />
                                        <x-input-error for="city" class="mt-2" />
                                    </div>

                                    <!-- Country -->
                                    <div class="col-span-6 sm:col-span-3">
                                        <x-label for="country" value="{{ his_trans('patients.country') }}" />
                                        <x-input id="country" name="country" type="text" class="mt-1 block w-full" :value="old('country', $patient->country)" />
                                        <x-input-error for="country" class="mt-2" />
                                    </div>

                                    <!-- Unique Master Citizen Number -->
                                    <div class="col-span-6">
                                        <x-label for="unique_master_citizen_number" value="{{ his_trans('patients.unique_master_citizen_number') }}" />
                                        <x-input id="unique_master_citizen_number" name="unique_master_citizen_number" type="text" class="mt-1 block w-full" :value="old('unique_master_citizen_number', $patient->unique_master_citizen_number)" />
                                        <x-input-error for="unique_master_citizen_number" class="mt-2" />
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <x-section-border />

                <!-- Notes -->
                <div class="mt-10 sm:mt-0">
                    <div class="md:grid md:grid-cols-3 md:gap-6">
                        <div class="md:col-span-1">
                            <div class="px-4 sm:px-0">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    {{ his_trans('patients.notes') }}
                                </h3>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    Additional notes about the patient.
                                </p>
                            </div>
                        </div>

                        <div class="mt-5 md:mt-0 md:col-span-2">
                            <div class="px-4 py-5 bg-white dark:bg-gray-800 sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
                                <div class="grid grid-cols-6 gap-6">
                                    <div class="col-span-6">
                                        <x-label for="notes" value="{{ his_trans('patients.notes') }}" />
                                        <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" placeholder="Additional notes about the patient...">{{ old('notes', $patient->notes) }}</textarea>
                                        <x-input-error for="notes" class="mt-2" />
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between px-4 py-3 bg-gray-50 dark:bg-gray-800 sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
                                <div class="flex items-center">
                                    @if($patient->visits()->count() === 0)
                                        <form method="POST" action="{{ route('patients.destroy', $patient) }}" onsubmit="return confirm('{{ his_trans('confirm_delete') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <x-danger-button type="submit">
                                                {{ his_trans('delete') }}
                                            </x-danger-button>
                                        </form>
                                    @endif
                                </div>

                                <div class="flex items-center">
                                    <x-secondary-button type="button" onclick="window.location.href='{{ route('patients.show', $patient) }}'">
                                        {{ his_trans('cancel') }}
                                    </x-secondary-button>

                                    <x-button class="ms-4">
                                        {{ his_trans('save') }}
                                    </x-button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>
