<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ his_trans('workspace.anamnesis') }} - {{ $visit->patient->full_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Workspace Navigation -->
            @include('visits.workspace.partials.navigation', ['visit' => $visit, 'active' => 'anamnesis'])

            <!-- Anamnesis Form -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ his_trans('anamnesis.title') }}</h3>
                        @if($visit->anamnesis && $visit->anamnesis->updated_at)
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                Last updated: {{ $visit->anamnesis->updated_at->format('M d, Y g:i A') }}
                            </div>
                        @endif
                    </div>

                    @if (session('success'))
                        <div class="rounded-md bg-green-50 dark:bg-green-900/20 p-4 mb-6">
                            <div class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}</div>
                        </div>
                    @endif

                    <!-- Anamnesis Form -->
                    <form action="{{ route('visits.anamnesis.store', $visit) }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Chief Complaint -->
                            <div class="lg:col-span-2">
                                <label for="chief_complaint" class="block text-sm font-medium text-gray-900 dark:text-white">
                                    {{ his_trans('anamnesis.chief_complaint') }}
                                </label>
                                <textarea name="chief_complaint" id="chief_complaint" rows="3"
                                          class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                          placeholder="Enter the patient's chief complaint...">{{ old('chief_complaint', $visit->anamnesis?->chief_complaint) }}</textarea>
                                @error('chief_complaint')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- History of Present Illness -->
                            <div class="lg:col-span-2">
                                <label for="history_of_present_illness" class="block text-sm font-medium text-gray-900 dark:text-white">
                                    {{ his_trans('anamnesis.history_of_present_illness') }}
                                </label>
                                <textarea name="history_of_present_illness" id="history_of_present_illness" rows="4"
                                          class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                          placeholder="Detailed history of the present illness...">{{ old('history_of_present_illness', $visit->anamnesis?->history_of_present_illness) }}</textarea>
                                @error('history_of_present_illness')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Past Medical History -->
                            <div>
                                <label for="past_medical_history" class="block text-sm font-medium text-gray-900 dark:text-white">
                                    {{ his_trans('anamnesis.past_medical_history') }}
                                </label>
                                <textarea name="past_medical_history" id="past_medical_history" rows="4"
                                          class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                          placeholder="Previous medical conditions and surgeries...">{{ old('past_medical_history', $visit->anamnesis?->past_medical_history) }}</textarea>
                                @error('past_medical_history')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Family History -->
                            <div>
                                <label for="family_history" class="block text-sm font-medium text-gray-900 dark:text-white">
                                    {{ his_trans('anamnesis.family_history') }}
                                </label>
                                <textarea name="family_history" id="family_history" rows="4"
                                          class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                          placeholder="Relevant family medical history...">{{ old('family_history', $visit->anamnesis?->family_history) }}</textarea>
                                @error('family_history')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Current Medications -->
                            <div>
                                <label for="medications_current" class="block text-sm font-medium text-gray-900 dark:text-white">
                                    {{ his_trans('anamnesis.medications_current') }}
                                </label>
                                <textarea name="medications_current" id="medications_current" rows="4"
                                          class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                          placeholder="Current medications and dosages...">{{ old('medications_current', $visit->anamnesis?->medications_current) }}</textarea>
                                @error('medications_current')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Allergies -->
                            <div>
                                <label for="allergies" class="block text-sm font-medium text-gray-900 dark:text-white">
                                    {{ his_trans('anamnesis.allergies') }}
                                </label>
                                <textarea name="allergies" id="allergies" rows="4"
                                          class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                          placeholder="Known allergies and reactions...">{{ old('allergies', $visit->anamnesis?->allergies) }}</textarea>
                                @error('allergies')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Other Notes -->
                            <div class="lg:col-span-2">
                                <label for="other_notes" class="block text-sm font-medium text-gray-900 dark:text-white">
                                    {{ his_trans('anamnesis.other_notes') }}
                                </label>
                                <textarea name="other_notes" id="other_notes" rows="3"
                                          class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                          placeholder="Any additional notes...">{{ old('other_notes', $visit->anamnesis?->other_notes) }}</textarea>
                                @error('other_notes')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-between pt-6">
                            <a href="{{ route('visits.show', $visit) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600">
                                ← {{ his_trans('back') }}
                            </a>

                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-600">
                                @if($visit->anamnesis)
                                    {{ his_trans('update') }} {{ his_trans('anamnesis.title') }}
                                @else
                                    {{ his_trans('save') }} {{ his_trans('anamnesis.title') }}
                                @endif
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
