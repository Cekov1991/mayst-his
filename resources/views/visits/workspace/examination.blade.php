@php
    $exam = $visit->ophthalmicExam;
    $refractions = $exam?->refractions ?? collect();
@endphp

<div class="space-y-8">
    <div class="flex justify-between items-center">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ his_trans('workspace.examination') }}</h3>
        @if(!$exam)
            <div class="text-sm text-gray-500 dark:text-gray-400">No examination recorded yet</div>
        @endif
    </div>

    @if (session('exam_success'))
        <div class="rounded-md bg-green-50 dark:bg-green-900/20 p-4">
            <div class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('exam_success') }}</div>
        </div>
    @endif

    <!-- Ophthalmic Examination Form -->
    <div class="bg-gray-50 dark:bg-gray-900/20 rounded-lg p-6">
        <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">{{ his_trans('exam.visual_acuity_and_iop') }}</h4>

        <form action="{{ route('visits.exam.store', $visit) }}" method="POST" class="space-y-6">
            @csrf
            @if($exam)
                @method('PUT')
            @endif

            <!-- Visual Acuity and IOP Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Visual Acuity OD -->
                <div>
                    <label for="visus_od" class="block text-sm font-medium text-gray-900 dark:text-white">
                        {{ his_trans('exam.visus_od') }}
                    </label>
                    <input type="text" name="visus_od" id="visus_od"
                           value="{{ old('visus_od', $exam?->visus_od) }}"
                           class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                           placeholder="20/20">
                    @error('visus_od')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Visual Acuity OS -->
                <div>
                    <label for="visus_os" class="block text-sm font-medium text-gray-900 dark:text-white">
                        {{ his_trans('exam.visus_os') }}
                    </label>
                    <input type="text" name="visus_os" id="visus_os"
                           value="{{ old('visus_os', $exam?->visus_os) }}"
                           class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                           placeholder="20/20">
                    @error('visus_os')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- IOP OD -->
                <div>
                    <label for="iop_od" class="block text-sm font-medium text-gray-900 dark:text-white">
                        {{ his_trans('exam.iop_od') }} (mmHg)
                    </label>
                    <input type="number" step="0.1" name="iop_od" id="iop_od"
                           value="{{ old('iop_od', $exam?->iop_od) }}"
                           class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                           placeholder="15.0">
                    @error('iop_od')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- IOP OS -->
                <div>
                    <label for="iop_os" class="block text-sm font-medium text-gray-900 dark:text-white">
                        {{ his_trans('exam.iop_os') }} (mmHg)
                    </label>
                    <input type="number" step="0.1" name="iop_os" id="iop_os"
                           value="{{ old('iop_os', $exam?->iop_os) }}"
                           class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                           placeholder="15.0">
                    @error('iop_os')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Clinical Findings -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Anterior Segment -->
                <div>
                    <label for="anterior_segment_findings" class="block text-sm font-medium text-gray-900 dark:text-white">
                        {{ his_trans('exam.anterior_segment_findings') }}
                    </label>
                    <textarea name="anterior_segment_findings" id="anterior_segment_findings" rows="4"
                              class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                              placeholder="Describe anterior segment findings...">{{ old('anterior_segment_findings', $exam?->anterior_segment_findings) }}</textarea>
                    @error('anterior_segment_findings')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Posterior Segment -->
                <div>
                    <label for="posterior_segment_findings" class="block text-sm font-medium text-gray-900 dark:text-white">
                        {{ his_trans('exam.posterior_segment_findings') }}
                    </label>
                    <textarea name="posterior_segment_findings" id="posterior_segment_findings" rows="4"
                              class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                              placeholder="Describe posterior segment findings...">{{ old('posterior_segment_findings', $exam?->posterior_segment_findings) }}</textarea>
                    @error('posterior_segment_findings')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-600">
                    @if($exam)
                        {{ his_trans('update') }}
                    @else
                        {{ his_trans('save') }}
                    @endif
                    {{ his_trans('workspace.examination') }}
                </button>
            </div>
        </form>
    </div>

    @if($exam)
        <!-- Refractions Section -->
        <div class="space-y-4">
            <div class="flex justify-between items-center">
                <h4 class="text-md font-medium text-gray-900 dark:text-white">{{ his_trans('refraction.title') }}</h4>
                <button onclick="document.getElementById('refraction-modal').classList.remove('hidden')"
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-600">
                    + {{ his_trans('refraction.add_refraction') }}
                </button>
            </div>

            @if($refractions->isNotEmpty())
                <!-- Refractions Table -->
                <x-table>
                    <x-slot name="head">
                        <x-table-header>Eye</x-table-header>
                        <x-table-header-secondary>Method</x-table-header-secondary>
                        <x-table-header-secondary>Sphere</x-table-header-secondary>
                        <x-table-header-secondary>Cylinder</x-table-header-secondary>
                        <x-table-header-secondary>Axis</x-table-header-secondary>
                        <x-table-header-secondary>Add</x-table-header-secondary>
                        <x-table-action-header>Actions</x-table-action-header>
                    </x-slot>

                    <x-slot name="body">
                        @foreach($refractions->sortBy(['eye', 'method']) as $refraction)
                            <x-table-row>
                                <x-table-cell header>
                                    <span class="font-medium">{{ $refraction->eye }}</span>
                                </x-table-cell>

                                <x-table-cell>
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300">
                                        {{ his_trans("refraction_methods.{$refraction->method}") }}
                                    </span>
                                </x-table-cell>

                                <x-table-cell>{{ $refraction->sphere ?? '—' }}</x-table-cell>
                                <x-table-cell>{{ $refraction->cylinder ?? '—' }}</x-table-cell>
                                <x-table-cell>{{ $refraction->axis ?? '—' }}</x-table-cell>
                                <x-table-cell>{{ $refraction->add_power ?? '—' }}</x-table-cell>

                                <x-table-action-cell>
                                    <form action="{{ route('visits.refractions.destroy', [$visit, $refraction]) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                onclick="return confirm('Are you sure?')"
                                                class="text-sm font-medium text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                            Delete
                                        </button>
                                    </form>
                                </x-table-action-cell>
                            </x-table-row>
                        @endforeach
                    </x-slot>
                </x-table>
            @else
                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                    <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <p class="text-sm">No refractions recorded yet</p>
                    <p class="text-xs mt-1">Add refraction measurements for this examination</p>
                </div>
            @endif
        </div>

        <!-- Add Refraction Modal -->
        <div id="refraction-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800">
                <div class="mt-3">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ his_trans('refraction.add_refraction') }}</h3>
                        <button onclick="document.getElementById('refraction-modal').classList.add('hidden')"
                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form action="{{ route('visits.refractions.store', $visit) }}" method="POST" class="space-y-4">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Eye -->
                            <div>
                                <label for="eye" class="block text-sm font-medium text-gray-900 dark:text-white">Eye</label>
                                <select name="eye" id="eye" required
                                        class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:focus:ring-indigo-500">
                                    <option value="">Select Eye</option>
                                    <option value="OD">OD (Right Eye)</option>
                                    <option value="OS">OS (Left Eye)</option>
                                </select>
                            </div>

                            <!-- Method -->
                            <div>
                                <label for="method" class="block text-sm font-medium text-gray-900 dark:text-white">Method</label>
                                <select name="method" id="method" required
                                        class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:focus:ring-indigo-500">
                                    <option value="">Select Method</option>
                                    <option value="autorefraction">Autorefraction</option>
                                    <option value="lensmeter">Lensmeter</option>
                                    <option value="subjective">Subjective</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Sphere -->
                            <div>
                                <label for="sphere" class="block text-sm font-medium text-gray-900 dark:text-white">Sphere</label>
                                <input type="number" step="0.25" name="sphere" id="sphere"
                                       class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                       placeholder="0.00">
                            </div>

                            <!-- Cylinder -->
                            <div>
                                <label for="cylinder" class="block text-sm font-medium text-gray-900 dark:text-white">Cylinder</label>
                                <input type="number" step="0.25" name="cylinder" id="cylinder"
                                       class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                       placeholder="0.00">
                            </div>

                            <!-- Axis -->
                            <div>
                                <label for="axis" class="block text-sm font-medium text-gray-900 dark:text-white">Axis</label>
                                <input type="number" min="1" max="180" name="axis" id="axis"
                                       class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                       placeholder="90">
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 pt-4">
                            <button type="button"
                                    onclick="document.getElementById('refraction-modal').classList.add('hidden')"
                                    class="px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-600">
                                Save Refraction
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    @if($exam && $exam->updated_at)
        <div class="text-sm text-gray-500 dark:text-gray-400">
            Last updated: {{ $exam->updated_at->format('M d, Y g:i A') }}
        </div>
    @endif
</div>
