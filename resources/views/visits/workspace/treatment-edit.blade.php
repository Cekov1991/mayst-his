<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit Treatment Plan - {{ $visit->patient->full_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Workspace Navigation -->
            @include('visits.workspace.partials.navigation', ['visit' => $visit, 'active' => 'treatments'])

            <!-- Treatment Form -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Edit Treatment Plan</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Update the treatment plan details.</p>
                    </div>

                    <!-- Treatment Form -->
                    <form action="{{ route('visits.treatments.update', [$visit, $treatment]) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Plan Type -->
                            <div>
                                <label for="plan_type" class="block text-sm font-medium text-gray-900 dark:text-white">{{ __('his.treatment.plan_type') }}</label>
                                <select name="plan_type" id="plan_type" required
                                        class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:focus:ring-indigo-500">
                                    <option value="">Select Type</option>
                                    <option value="surgery" {{ old('plan_type', $treatment->plan_type) === 'surgery' ? 'selected' : '' }}>Surgery</option>
                                    <option value="injection" {{ old('plan_type', $treatment->plan_type) === 'injection' ? 'selected' : '' }}>Injection</option>
                                    <option value="medical" {{ old('plan_type', $treatment->plan_type) === 'medical' ? 'selected' : '' }}>Medical Treatment</option>
                                    <option value="advice" {{ old('plan_type', $treatment->plan_type) === 'advice' ? 'selected' : '' }}>Advice</option>
                                </select>
                                @error('plan_type')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-900 dark:text-white">{{ __('his.treatment.status') }}</label>
                                <select name="status" id="status" required
                                        class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:focus:ring-indigo-500">
                                    <option value="proposed" {{ old('status', $treatment->status) === 'proposed' ? 'selected' : '' }}>Proposed</option>
                                    <option value="accepted" {{ old('status', $treatment->status) === 'accepted' ? 'selected' : '' }}>Accepted</option>
                                    <option value="scheduled" {{ old('status', $treatment->status) === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                    <option value="done" {{ old('status', $treatment->status) === 'done' ? 'selected' : '' }}>Done</option>
                                    <option value="declined" {{ old('status', $treatment->status) === 'declined' ? 'selected' : '' }}>Declined</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Recommendation -->
                        <div>
                            <label for="recommendation" class="block text-sm font-medium text-gray-900 dark:text-white">{{ __('his.treatment.recommendation') }}</label>
                            <input type="text" name="recommendation" id="recommendation" value="{{ old('recommendation', $treatment->recommendation) }}" required
                                   class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                   placeholder="Brief treatment recommendation...">
                            @error('recommendation')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Details -->
                        <div>
                            <label for="details" class="block text-sm font-medium text-gray-900 dark:text-white">{{ __('his.treatment.details') }}</label>
                            <textarea name="details" id="details" rows="4"
                                      class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                      placeholder="Detailed treatment plan and instructions...">{{ old('details', $treatment->details) }}</textarea>
                            @error('details')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Planned Date -->
                        <div>
                            <label for="planned_date" class="block text-sm font-medium text-gray-900 dark:text-white">{{ __('his.treatment.planned_date') }}</label>
                            <input type="date" name="planned_date" id="planned_date" value="{{ old('planned_date', $treatment->planned_date?->format('Y-m-d')) }}"
                                   class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:focus:ring-indigo-500">
                            @error('planned_date')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-between pt-6">
                            <a href="{{ route('visits.treatments', $visit) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600">
                                ← {{ __('his.back') }}
                            </a>

                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-600">
                                {{ __('his.update') }} Treatment Plan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
