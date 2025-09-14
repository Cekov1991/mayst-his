<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('his.patients.add_patient') }}
            </h2>
            <x-locale-switcher />
        </div>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('patients.store') }}">
                @csrf

                <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                        <div class="px-4 sm:px-0">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('his.patients.patient_details') }}
                            </h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                This information will be displayed on the patient record.
                            </p>
                        </div>
                    </div>

                    <div class="mt-5 md:mt-0 md:col-span-2">
                        <div class="px-4 py-5 bg-white dark:bg-gray-800 sm:p-6 shadow sm:rounded-md">
                            <div class="grid grid-cols-6 gap-6">

                                <!-- First Name -->
                                <div class="col-span-6 sm:col-span-3">
                                    <x-label for="first_name" value="{{ __('his.patients.first_name') }}" />
                                    <x-input id="first_name" name="first_name" type="text" class="mt-1 block w-full" :value="old('first_name')" required autofocus />
                                    <x-input-error for="first_name" class="mt-2" />
                                </div>

                                <!-- Last Name -->
                                <div class="col-span-6 sm:col-span-3">
                                    <x-label for="last_name" value="{{ __('his.patients.last_name') }}" />
                                    <x-input id="last_name" name="last_name" type="text" class="mt-1 block w-full" :value="old('last_name')" required />
                                    <x-input-error for="last_name" class="mt-2" />
                                </div>

                                <!-- Sex -->
                                <div class="col-span-6 sm:col-span-3">
                                    <x-label for="sex" value="{{ __('his.patients.sex') }}" />
                                    <select id="sex" name="sex" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                        <option value="">{{ __('his.patients.sex') }}...</option>
                                        @foreach(['male', 'female', 'other', 'unknown'] as $sexOption)
                                            <option value="{{ $sexOption }}" {{ old('sex') === $sexOption ? 'selected' : '' }}>
                                                {{ __("his.sex_options.{$sexOption}") }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error for="sex" class="mt-2" />
                                </div>

                                <!-- Date of Birth -->
                                <div class="col-span-6 sm:col-span-3">
                                    <x-label for="dob" value="{{ __('his.patients.dob') }}" />
                                    <x-input id="dob" name="dob" type="date" class="mt-1 block w-full" :value="old('dob')" max="{{ date('Y-m-d') }}" required />
                                    <x-input-error for="dob" class="mt-2" />
                                </div>

                                <!-- Phone -->
                                <div class="col-span-6 sm:col-span-3">
                                    <x-label for="phone" value="{{ __('his.patients.phone') }}" />
                                    <x-input id="phone" name="phone" type="tel" class="mt-1 block w-full" :value="old('phone')" />
                                    <x-input-error for="phone" class="mt-2" />
                                </div>

                                <!-- Email -->
                                <div class="col-span-6 sm:col-span-3">
                                    <x-label for="email" value="{{ __('his.patients.email') }}" />
                                    <x-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" />
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
                                        <x-label for="address" value="{{ __('his.patients.address') }}" />
                                        <textarea id="address" name="address" rows="2" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('address') }}</textarea>
                                        <x-input-error for="address" class="mt-2" />
                                    </div>

                                    <!-- City -->
                                    <div class="col-span-6 sm:col-span-3">
                                        <x-label for="city" value="{{ __('his.patients.city') }}" />
                                        <x-input id="city" name="city" type="text" class="mt-1 block w-full" :value="old('city')" />
                                        <x-input-error for="city" class="mt-2" />
                                    </div>

                                    <!-- Country -->
                                    <div class="col-span-6 sm:col-span-3">
                                        <x-label for="country" value="{{ __('his.patients.country') }}" />
                                        <x-input id="country" name="country" type="text" class="mt-1 block w-full" :value="old('country', 'Macedonia')" />
                                        <x-input-error for="country" class="mt-2" />
                                    </div>

                                    <!-- Unique Master Citizen Number -->
                                    <div class="col-span-6">
                                        <x-label for="unique_master_citizen_number" value="{{ __('his.patients.unique_master_citizen_number') }}" />
                                        <x-input id="unique_master_citizen_number" name="unique_master_citizen_number" type="text" class="mt-1 block w-full" :value="old('unique_master_citizen_number')" />
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
                                    {{ __('his.patients.notes') }}
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
                                        <x-label for="notes" value="{{ __('his.patients.notes') }}" />
                                        <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" placeholder="Additional notes about the patient...">{{ old('notes') }}</textarea>
                                        <x-input-error for="notes" class="mt-2" />
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-end px-4 py-3 bg-gray-50 dark:bg-gray-800 text-end sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
                                <x-secondary-button type="button" onclick="window.location.href='{{ route('patients.index') }}'">
                                    {{ __('his.cancel') }}
                                </x-secondary-button>

                                <x-button class="ms-4">
                                    {{ __('his.save') }}
                                </x-button>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>
