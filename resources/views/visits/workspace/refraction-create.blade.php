<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Add Refraction - {{ $visit->patient->full_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Workspace Navigation -->
            @include('visits.workspace.partials.navigation', ['visit' => $visit, 'active' => 'examination'])

            <!-- Refraction Form -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('refraction.add_refraction') }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Add a new refraction measurement for this examination.</p>
                    </div>

                    <!-- Refraction Form -->
                    <form action="{{ route('visits.refractions.store', $visit) }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Eye -->
                            <div>
                                <label for="eye" class="block text-sm font-medium text-gray-900 dark:text-white">Eye</label>
                                <select name="eye" id="eye" required
                                        class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:focus:ring-indigo-500">
                                    <option value="">Select Eye</option>
                                    <option value="OD" {{ old('eye') === 'OD' ? 'selected' : '' }}>OD (Right Eye)</option>
                                    <option value="OS" {{ old('eye') === 'OS' ? 'selected' : '' }}>OS (Left Eye)</option>
                                </select>
                                @error('eye')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Method -->
                            <div>
                                <label for="method" class="block text-sm font-medium text-gray-900 dark:text-white">Method</label>
                                <select name="method" id="method" required
                                        class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:focus:ring-indigo-500">
                                    <option value="">Select Method</option>
                                    <option value="autorefraction" {{ old('method') === 'autorefraction' ? 'selected' : '' }}>{{ __('refraction_methods.autorefraction') }}</option>
                                    <option value="lensmeter" {{ old('method') === 'lensmeter' ? 'selected' : '' }}>{{ __('refraction_methods.lensmeter') }}</option>
                                    <option value="subjective" {{ old('method') === 'subjective' ? 'selected' : '' }}>{{ __('refraction_methods.subjective') }}</option>
                                </select>
                                @error('method')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Sphere -->
                            <div>
                                <label for="sphere" class="block text-sm font-medium text-gray-900 dark:text-white">{{ __('refraction.sphere') }}</label>
                                <input type="number" step="0.25" name="sphere" id="sphere" value="{{ old('sphere') }}"
                                       class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                       placeholder="0.00">
                                @error('sphere')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Cylinder -->
                            <div>
                                <label for="cylinder" class="block text-sm font-medium text-gray-900 dark:text-white">{{ __('refraction.cylinder') }}</label>
                                <input type="number" step="0.25" name="cylinder" id="cylinder" value="{{ old('cylinder') }}"
                                       class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                       placeholder="0.00">
                                @error('cylinder')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Axis -->
                            <div>
                                <label for="axis" class="block text-sm font-medium text-gray-900 dark:text-white">{{ __('refraction.axis') }}</label>
                                <input type="number" min="1" max="180" name="axis" id="axis" value="{{ old('axis') }}"
                                       class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                       placeholder="90">
                                @error('axis')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Add Power -->
                            <div>
                                <label for="add_power" class="block text-sm font-medium text-gray-900 dark:text-white">Add Power</label>
                                <input type="number" step="0.25" name="add_power" id="add_power" value="{{ old('add_power') }}"
                                       class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                       placeholder="0.00">
                                @error('add_power')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Prism -->
                            <div>
                                <label for="prism" class="block text-sm font-medium text-gray-900 dark:text-white">Prism</label>
                                <input type="number" step="0.25" name="prism" id="prism" value="{{ old('prism') }}"
                                       class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                       placeholder="0.00">
                                @error('prism')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Base -->
                            <div>
                                <label for="base" class="block text-sm font-medium text-gray-900 dark:text-white">Base</label>
                                <select name="base" id="base"
                                        class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:focus:ring-indigo-500">
                                    <option value="">Select Base</option>
                                    <option value="up" {{ old('base') === 'up' ? 'selected' : '' }}>Up</option>
                                    <option value="down" {{ old('base') === 'down' ? 'selected' : '' }}>Down</option>
                                    <option value="in" {{ old('base') === 'in' ? 'selected' : '' }}>In</option>
                                    <option value="out" {{ old('base') === 'out' ? 'selected' : '' }}>Out</option>
                                </select>
                                @error('base')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-900 dark:text-white">Notes</label>
                            <textarea name="notes" id="notes" rows="3"
                                      class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                      placeholder="Additional notes about this refraction...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-between pt-6">
                            <a href="{{ route('visits.examination', $visit) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600">
                                ← {{ __('common.back') }}
                            </a>

                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-600">
                                {{ __('common.save') }} Refraction
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
