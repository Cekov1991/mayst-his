<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('diagnoses.add_diagnosis') }} - {{ $visit->patient->full_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Workspace Navigation -->
            @include('visits.workspace.partials.navigation', ['visit' => $visit, 'active' => 'diagnoses'])

            <!-- Diagnosis Create Form -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('diagnoses.add_diagnosis') }}</h3>
                        <a href="{{ route('visits.diagnoses', $visit) }}"
                           class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:bg-gray-50 disabled:opacity-25 transition">
                            {{ __('common.back') }}
                        </a>
                    </div>

                    <!-- Diagnosis Form -->
                    <form action="{{ route('visits.diagnosis.store', $visit) }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Global Validation Errors -->
                        @if ($errors->any())
                            <div class="rounded-md bg-red-50 dark:bg-red-900/20 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                                            There were errors with your submission
                                        </h3>
                                        <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                                            <ul role="list" class="list-disc space-y-1 pl-5">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Diagnosis Term -->
                            <div class="md:col-span-2">
                                <label for="term" class="block text-sm font-medium text-gray-900 dark:text-white">
                                    {{ __('diagnoses.term') }} *
                                </label>
                                <input type="text" name="term" id="term"
                                       value="{{ old('term') }}"
                                       class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                       placeholder="{{ __('diagnoses.term_placeholder') }}" required>
                                @error('term')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Eye -->
                            <div>
                                <label for="eye" class="block text-sm font-medium text-gray-900 dark:text-white">
                                    {{ __('diagnoses.eye') }} *
                                </label>
                                <select name="eye" id="eye"
                                        class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:focus:ring-indigo-500" required>
                                    <option value="">{{ __('diagnoses.select_eye') }}</option>
                                    <option value="OD" {{ old('eye') === 'OD' ? 'selected' : '' }}>{{ __('diagnoses.od') }}</option>
                                    <option value="OS" {{ old('eye') === 'OS' ? 'selected' : '' }}>{{ __('diagnoses.os') }}</option>
                                    <option value="OU" {{ old('eye') === 'OU' ? 'selected' : '' }}>{{ __('diagnoses.ou') }}</option>
                                    <option value="NA" {{ old('eye') === 'NA' ? 'selected' : '' }}>{{ __('diagnoses.na') }}</option>
                                </select>
                                @error('eye')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-900 dark:text-white">
                                    {{ __('diagnoses.status') }} *
                                </label>
                                <select name="status" id="status"
                                        class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:focus:ring-indigo-500" required>
                                    <option value="provisional" {{ old('status', 'provisional') === 'provisional' ? 'selected' : '' }}>{{ __('diagnoses.provisional') }}</option>
                                    <option value="working" {{ old('status') === 'working' ? 'selected' : '' }}>{{ __('diagnoses.working') }}</option>
                                    <option value="confirmed" {{ old('status') === 'confirmed' ? 'selected' : '' }}>{{ __('diagnoses.confirmed') }}</option>
                                    <option value="ruled_out" {{ old('status') === 'ruled_out' ? 'selected' : '' }}>{{ __('diagnoses.ruled_out') }}</option>
                                    <option value="resolved" {{ old('status') === 'resolved' ? 'selected' : '' }}>{{ __('diagnoses.resolved') }}</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Severity -->
                            <div>
                                <label for="severity" class="block text-sm font-medium text-gray-900 dark:text-white">
                                    {{ __('diagnoses.severity') }} *
                                </label>
                                <select name="severity" id="severity"
                                        class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:focus:ring-indigo-500" required>
                                    <option value="unknown" {{ old('severity', 'unknown') === 'unknown' ? 'selected' : '' }}>{{ __('diagnoses.unknown') }}</option>
                                    <option value="mild" {{ old('severity') === 'mild' ? 'selected' : '' }}>{{ __('diagnoses.mild') }}</option>
                                    <option value="moderate" {{ old('severity') === 'moderate' ? 'selected' : '' }}>{{ __('diagnoses.moderate') }}</option>
                                    <option value="severe" {{ old('severity') === 'severe' ? 'selected' : '' }}>{{ __('diagnoses.severe') }}</option>
                                </select>
                                @error('severity')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Acuity -->
                            <div>
                                <label for="acuity" class="block text-sm font-medium text-gray-900 dark:text-white">
                                    {{ __('diagnoses.acuity') }} *
                                </label>
                                <select name="acuity" id="acuity"
                                        class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:focus:ring-indigo-500" required>
                                    <option value="unknown" {{ old('acuity', 'unknown') === 'unknown' ? 'selected' : '' }}>{{ __('diagnoses.unknown') }}</option>
                                    <option value="acute" {{ old('acuity') === 'acute' ? 'selected' : '' }}>{{ __('diagnoses.acute') }}</option>
                                    <option value="subacute" {{ old('acuity') === 'subacute' ? 'selected' : '' }}>{{ __('diagnoses.subacute') }}</option>
                                    <option value="chronic" {{ old('acuity') === 'chronic' ? 'selected' : '' }}>{{ __('diagnoses.chronic') }}</option>
                                </select>
                                @error('acuity')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Diagnosed By -->
                            <div>
                                <label for="diagnosed_by" class="block text-sm font-medium text-gray-900 dark:text-white">
                                    {{ __('diagnoses.diagnosed_by') }} *
                                </label>
                                <select name="diagnosed_by" id="diagnosed_by"
                                        class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:focus:ring-indigo-500" required>
                                    <option value="">{{ __('diagnoses.select_doctor') }}</option>
                                    @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}" {{ old('diagnosed_by', auth()->id()) == $doctor->id ? 'selected' : '' }}>
                                            {{ $doctor->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('diagnosed_by')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Onset Date -->
                            <div>
                                <label for="onset_date" class="block text-sm font-medium text-gray-900 dark:text-white">
                                    {{ __('diagnoses.onset_date') }}
                                </label>
                                <input type="date" name="onset_date" id="onset_date"
                                       value="{{ old('onset_date') }}"
                                       class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500">
                                @error('onset_date')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Code -->
                            <div>
                                <label for="code" class="block text-sm font-medium text-gray-900 dark:text-white">
                                    {{ __('diagnoses.code') }}
                                </label>
                                <input type="text" name="code" id="code"
                                       value="{{ old('code') }}"
                                       class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                       placeholder="{{ __('diagnoses.code_placeholder') }}">
                                @error('code')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Code System -->
                            <div>
                                <label for="code_system" class="block text-sm font-medium text-gray-900 dark:text-white">
                                    {{ __('diagnoses.code_system') }}
                                </label>
                                <select name="code_system" id="code_system"
                                        class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:focus:ring-indigo-500">
                                    <option value="">{{ __('diagnoses.select_code_system') }}</option>
                                    <option value="ICD-10" {{ old('code_system') === 'ICD-10' ? 'selected' : '' }}>ICD-10</option>
                                    <option value="ICD-11" {{ old('code_system') === 'ICD-11' ? 'selected' : '' }}>ICD-11</option>
                                    <option value="SNOMED" {{ old('code_system') === 'SNOMED' ? 'selected' : '' }}>SNOMED</option>
                                    <option value="LOCAL" {{ old('code_system') === 'LOCAL' ? 'selected' : '' }}>{{ __('diagnoses.local') }}</option>
                                </select>
                                @error('code_system')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Primary Diagnosis -->
                            <div class="md:col-span-2">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="is_primary" name="is_primary" type="checkbox" value="1"
                                               {{ old('is_primary') ? 'checked' : '' }}
                                               class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded dark:bg-white/5 dark:border-white/10">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="is_primary" class="font-medium text-gray-700 dark:text-gray-300">
                                            {{ __('diagnoses.is_primary') }}
                                        </label>
                                        <p class="text-gray-500 dark:text-gray-400">{{ __('diagnoses.primary_help') }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Notes -->
                            <div class="md:col-span-2">
                                <label for="notes" class="block text-sm font-medium text-gray-900 dark:text-white">
                                    {{ __('diagnoses.notes_placeholder') }}
                                </label>
                                <textarea name="notes" id="notes" rows="4"
                                          class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                          placeholder="{{ __('diagnoses.notes_placeholder') }}">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('visits.diagnoses', $visit) }}"
                               class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:bg-gray-50 disabled:opacity-25 transition">
                                {{ __('cancel') }}
                            </a>
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-200 active:bg-indigo-600 disabled:opacity-25 transition">
                                {{ __('common.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
