<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('imaging.order_study') }} - {{ $visit->patient->full_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Workspace Navigation -->
            @include('visits.workspace.partials.navigation', ['visit' => $visit, 'active' => 'imaging'])

            <!-- Imaging Form -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('imaging.order_study') }}</h3>
                    </div>

                    <!-- Imaging Form -->
                    <form action="{{ route('visits.imaging.store', $visit) }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Modality -->
                            <div>
                                <label for="modality" class="block text-sm font-medium text-gray-900 dark:text-white">{{ __('imaging.modality') }}</label>
                                <select name="modality" id="modality" required
                                        class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:focus:ring-indigo-500">
                                    <option value="">{{ __('common.select_option') }}</option>
                                    <option value="OCT" {{ old('modality') === 'OCT' ? 'selected' : '' }}>{{ __('imaging.modalities.OCT') }}</option>
                                    <option value="VF" {{ old('modality') === 'VF' ? 'selected' : '' }}>{{ __('imaging.modalities.VF') }}</option>
                                    <option value="US" {{ old('modality') === 'US' ? 'selected' : '' }}>{{ __('imaging.modalities.US') }}</option>
                                    <option value="FA" {{ old('modality') === 'FA' ? 'selected' : '' }}>{{ __('imaging.modalities.FA') }}</option>
                                    <option value="Biometry" {{ old('modality') === 'Biometry' ? 'selected' : '' }}>{{ __('imaging.modalities.Biometry') }}</option>
                                    <option value="Photo" {{ old('modality') === 'Photo' ? 'selected' : '' }}>{{ __('imaging.modalities.Photo') }}</option>
                                    <option value="Other" {{ old('modality') === 'Other' ? 'selected' : '' }}>{{ __('imaging.modalities.Other') }}</option>
                                </select>
                                @error('modality')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Eye -->
                            <div>
                                <label for="eye" class="block text-sm font-medium text-gray-900 dark:text-white">{{ __('imaging.eye') }}</label>
                                <select name="eye" id="eye" required
                                        class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:focus:ring-indigo-500">
                                    <option value="">{{ __('common.select_option') }}</option>
                                    <option value="OD" {{ old('eye') === 'OD' ? 'selected' : '' }}>{{ __('imaging.eyes.OD') }}</option>
                                    <option value="OS" {{ old('eye') === 'OS' ? 'selected' : '' }}>{{ __('imaging.eyes.OS') }}</option>
                                    <option value="OU" {{ old('eye') === 'OU' ? 'selected' : '' }}>{{ __('imaging.eyes.OU') }}</option>
                                    <option value="NA" {{ old('eye') === 'NA' ? 'selected' : '' }}>{{ __('imaging.eyes.NA') }}</option>
                                </select>
                                @error('eye')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-900 dark:text-white">{{ __('imaging.status') }}</label>
                                <select name="status" id="status" required
                                        class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:focus:ring-indigo-500">
                                    <option value="ordered" {{ old('status') === 'ordered' ? 'selected' : '' }}>{{ __('imaging.statuses.ordered') }}</option>
                                    <option value="done" {{ old('status') === 'done' ? 'selected' : '' }}>{{ __('imaging.statuses.done') }}</option>
                                    <option value="reported" {{ old('status') === 'reported' ? 'selected' : '' }}>{{ __('imaging.statuses.reported') }}</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Performed Date -->
                            <div>
                                <label for="performed_at" class="block text-sm font-medium text-gray-900 dark:text-white">{{ __('imaging.performed_at') }}</label>
                                <input type="datetime-local" name="performed_at" id="performed_at" value="{{ old('performed_at') }}"
                                       class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:focus:ring-indigo-500">
                                @error('performed_at')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Findings -->
                        <div>
                            <label for="findings" class="block text-sm font-medium text-gray-900 dark:text-white">{{ __('imaging.findings') }}</label>
                            <textarea name="findings" id="findings" rows="4"
                                      class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                      placeholder="Enter imaging findings and interpretation...">{{ old('findings') }}</textarea>
                            @error('findings')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-between pt-6">
                            <a href="{{ route('visits.imaging', $visit) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600">
                                ← {{ __('common.back') }}
                            </a>

                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-600">
                                {{ __('common.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
