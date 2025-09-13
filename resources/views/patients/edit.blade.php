<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ his_trans('patients.edit_patient') }} - {{ $patient->full_name }}
            </h2>
            <x-locale-switcher />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <form method="POST" action="{{ route('patients.update', $patient) }}" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Personal Information Section -->
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100 mb-4">
                            {{ his_trans('patients.patient_details') }}
                        </h3>

                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                            <!-- First Name -->
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ his_trans('patients.first_name') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       name="first_name"
                                       id="first_name"
                                       value="{{ old('first_name', $patient->first_name) }}"
                                       required
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('first_name') border-red-500 @enderror">
                                @error('first_name')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Last Name -->
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ his_trans('patients.last_name') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       name="last_name"
                                       id="last_name"
                                       value="{{ old('last_name', $patient->last_name) }}"
                                       required
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('last_name') border-red-500 @enderror">
                                @error('last_name')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Sex -->
                            <div>
                                <label for="sex" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ his_trans('patients.sex') }} <span class="text-red-500">*</span>
                                </label>
                                <select name="sex"
                                        id="sex"
                                        required
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('sex') border-red-500 @enderror">
                                    <option value="">{{ his_trans('patients.sex') }}...</option>
                                    @foreach(['male', 'female', 'other', 'unknown'] as $sexOption)
                                        <option value="{{ $sexOption }}"
                                                {{ old('sex', $patient->sex) === $sexOption ? 'selected' : '' }}>
                                            {{ his_trans("sex_options.{$sexOption}") }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('sex')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Date of Birth -->
                            <div>
                                <label for="dob" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ his_trans('patients.dob') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="date"
                                       name="dob"
                                       id="dob"
                                       value="{{ old('dob', $patient->dob->format('Y-m-d')) }}"
                                       required
                                       max="{{ date('Y-m-d') }}"
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('dob') border-red-500 @enderror">
                                @error('dob')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ his_trans('patients.phone') }}
                                </label>
                                <input type="tel"
                                       name="phone"
                                       id="phone"
                                       value="{{ old('phone', $patient->phone) }}"
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('phone') border-red-500 @enderror">
                                @error('phone')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ his_trans('patients.email') }}
                                </label>
                                <input type="email"
                                       name="email"
                                       id="email"
                                       value="{{ old('email', $patient->email) }}"
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('email') border-red-500 @enderror">
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Address Information Section -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100 mb-4">
                            Address Information
                        </h3>

                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                            <!-- Address -->
                            <div class="sm:col-span-2">
                                <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ his_trans('patients.address') }}
                                </label>
                                <textarea name="address"
                                          id="address"
                                          rows="2"
                                          class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('address') border-red-500 @enderror">{{ old('address', $patient->address) }}</textarea>
                                @error('address')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- City -->
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ his_trans('patients.city') }}
                                </label>
                                <input type="text"
                                       name="city"
                                       id="city"
                                       value="{{ old('city', $patient->city) }}"
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('city') border-red-500 @enderror">
                                @error('city')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Country -->
                            <div>
                                <label for="country" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ his_trans('patients.country') }}
                                </label>
                                <input type="text"
                                       name="country"
                                       id="country"
                                       value="{{ old('country', $patient->country) }}"
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('country') border-red-500 @enderror">
                                @error('country')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Unique Master Citizen Number -->
                            <div class="sm:col-span-2">
                                <label for="unique_master_citizen_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ his_trans('patients.unique_master_citizen_number') }}
                                </label>
                                <input type="text"
                                       name="unique_master_citizen_number"
                                       id="unique_master_citizen_number"
                                       value="{{ old('unique_master_citizen_number', $patient->unique_master_citizen_number) }}"
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('unique_master_citizen_number') border-red-500 @enderror">
                                @error('unique_master_citizen_number')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Notes Section -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ his_trans('patients.notes') }}
                            </label>
                            <textarea name="notes"
                                      id="notes"
                                      rows="3"
                                      placeholder="Additional notes about the patient..."
                                      class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('notes') border-red-500 @enderror">{{ old('notes', $patient->notes) }}</textarea>
                            @error('notes')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6 flex items-center justify-between">
                        <div class="flex space-x-4">
                            <a href="{{ route('patients.show', $patient) }}"
                               class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500 dark:hover:bg-gray-700">
                                {{ his_trans('cancel') }}
                            </a>
                            <button type="submit"
                                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                {{ his_trans('save') }}
                            </button>
                        </div>

                        <!-- Delete Button (if no visits exist) -->
                        @if($patient->visits()->count() === 0)
                        <form method="POST" action="{{ route('patients.destroy', $patient) }}"
                              onsubmit="return confirm('{{ his_trans('confirm_delete') }}')"
                              class="ml-4">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                {{ his_trans('delete') }}
                            </button>
                        </form>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
