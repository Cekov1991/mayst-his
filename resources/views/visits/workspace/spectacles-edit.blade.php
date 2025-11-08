<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit Spectacle Prescription - {{ $visit->patient->full_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Workspace Navigation -->
            @include('visits.workspace.partials.navigation', ['visit' => $visit, 'active' => 'spectacles'])

            <!-- Spectacle Form -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Edit Spectacle Prescription</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Update the spectacle prescription details.</p>
                    </div>

                    <!-- Spectacle Form -->
                    <form action="{{ route('visits.spectacles.update', [$visit, $spectacle]) }}" method="POST" class="space-y-8">
                        @csrf
                        @method('PUT')

                        <!-- Prescription Type and Validity -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-900 dark:text-white">{{ __('spectacles.type') }}</label>
                                <select name="type" id="type" required
                                        class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:focus:ring-indigo-500"
                                        onchange="toggleAddFields()">
                                    <option value="distance" {{ old('type', $spectacle->type) === 'distance' ? 'selected' : '' }}>Distance</option>
                                    <option value="near" {{ old('type', $spectacle->type) === 'near' ? 'selected' : '' }}>Near</option>
                                    <option value="bifocal" {{ old('type', $spectacle->type) === 'bifocal' ? 'selected' : '' }}>Bifocal</option>
                                    <option value="progressive" {{ old('type', $spectacle->type) === 'progressive' ? 'selected' : '' }}>Progressive</option>
                                </select>
                                @error('type')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="valid_until" class="block text-sm font-medium text-gray-900 dark:text-white">{{ __('spectacles.valid_until') }}</label>
                                <input type="date" name="valid_until" id="valid_until" value="{{ old('valid_until', $spectacle->valid_until?->format('Y-m-d')) }}"
                                       class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:focus:ring-indigo-500">
                                @error('valid_until')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Right Eye (OD) -->
                        <div class="bg-gray-50 dark:bg-gray-900/20 p-6 rounded-lg">
                            <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">OD (Right Eye)</h4>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div>
                                    <label for="od_sphere" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sphere</label>
                                    <input type="number" step="0.25" name="od_sphere" id="od_sphere" value="{{ old('od_sphere', $spectacle->od_sphere) }}"
                                           class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                           placeholder="0.00">
                                    @error('od_sphere')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="od_cylinder" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cylinder</label>
                                    <input type="number" step="0.25" name="od_cylinder" id="od_cylinder" value="{{ old('od_cylinder', $spectacle->od_cylinder) }}"
                                           class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                           placeholder="0.00">
                                    @error('od_cylinder')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="od_axis" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Axis</label>
                                    <input type="number" min="1" max="180" name="od_axis" id="od_axis" value="{{ old('od_axis', $spectacle->od_axis) }}"
                                           class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                           placeholder="90">
                                    @error('od_axis')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div id="od_add_field" style="display: none;">
                                    <label for="od_add" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Add</label>
                                    <input type="number" step="0.25" name="od_add" id="od_add" value="{{ old('od_add', $spectacle->od_add) }}"
                                           class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                           placeholder="0.00">
                                    @error('od_add')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Left Eye (OS) -->
                        <div class="bg-gray-50 dark:bg-gray-900/20 p-6 rounded-lg">
                            <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">OS (Left Eye)</h4>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div>
                                    <label for="os_sphere" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sphere</label>
                                    <input type="number" step="0.25" name="os_sphere" id="os_sphere" value="{{ old('os_sphere', $spectacle->os_sphere) }}"
                                           class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                           placeholder="0.00">
                                    @error('os_sphere')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="os_cylinder" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cylinder</label>
                                    <input type="number" step="0.25" name="os_cylinder" id="os_cylinder" value="{{ old('os_cylinder', $spectacle->os_cylinder) }}"
                                           class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                           placeholder="0.00">
                                    @error('os_cylinder')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="os_axis" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Axis</label>
                                    <input type="number" min="1" max="180" name="os_axis" id="os_axis" value="{{ old('os_axis', $spectacle->os_axis) }}"
                                           class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                           placeholder="90">
                                    @error('os_axis')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div id="os_add_field" style="display: none;">
                                    <label for="os_add" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Add</label>
                                    <input type="number" step="0.25" name="os_add" id="os_add" value="{{ old('os_add', $spectacle->os_add) }}"
                                           class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                           placeholder="0.00">
                                    @error('os_add')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- PD and Notes -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="pd_distance" class="block text-sm font-medium text-gray-900 dark:text-white">PD Distance (mm)</label>
                                <input type="number" step="0.5" name="pd_distance" id="pd_distance" value="{{ old('pd_distance', $spectacle->pd_distance) }}"
                                       class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                       placeholder="63.0">
                                @error('pd_distance')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="pd_near" class="block text-sm font-medium text-gray-900 dark:text-white">PD Near (mm)</label>
                                <input type="number" step="0.5" name="pd_near" id="pd_near" value="{{ old('pd_near', $spectacle->pd_near) }}"
                                       class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                       placeholder="60.0">
                                @error('pd_near')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-900 dark:text-white">{{ __('spectacles.notes') }}</label>
                            <textarea name="notes" id="notes" rows="3"
                                      class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                      placeholder="Additional notes for the optician...">{{ old('notes', $spectacle->notes) }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-between pt-6">
                            <a href="{{ route('visits.spectacles', $visit) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600">
                                ← {{ __('common.back') }}
                            </a>

                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-600">
                                {{ __('common.update') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    function toggleAddFields() {
        const type = document.getElementById('type').value;
        const odAddField = document.getElementById('od_add_field');
        const osAddField = document.getElementById('os_add_field');

        if (type === 'bifocal' || type === 'progressive') {
            odAddField.style.display = 'block';
            osAddField.style.display = 'block';
        } else {
            odAddField.style.display = 'none';
            osAddField.style.display = 'none';
        }
    }

    // Initialize add fields visibility on page load
    document.addEventListener('DOMContentLoaded', function() {
        toggleAddFields();
    });
    </script>
</x-app-layout>
