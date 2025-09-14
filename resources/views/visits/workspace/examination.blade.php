<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('his.workspace.examination') }} - {{ $visit->patient->full_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Workspace Navigation -->
            @include('visits.workspace.partials.navigation', ['visit' => $visit, 'active' => 'examination'])

            <!-- Examination Form -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('his.exam.visual_acuity_and_iop') }}</h3>
                        @if($visit->ophthalmicExam && $visit->ophthalmicExam->updated_at)
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                Last updated: {{ $visit->ophthalmicExam->updated_at->format('M d, Y g:i A') }}
                            </div>
                        @endif
                    </div>

                    @if (session('success'))
                        <div class="rounded-md bg-green-50 dark:bg-green-900/20 p-4 mb-6">
                            <div class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}</div>
                        </div>
                    @endif

                    <!-- Ophthalmic Examination Form -->
                    <form action="{{ route('visits.exam.store', $visit) }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Visual Acuity and IOP Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- Visual Acuity OD -->
                            <div>
                                <label for="visus_od" class="block text-sm font-medium text-gray-900 dark:text-white">
                                    {{ __('his.exam.visus_od') }}
                                </label>
                                <input type="text" name="visus_od" id="visus_od"
                                       value="{{ old('visus_od', $visit->ophthalmicExam?->visus_od) }}"
                                       class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                       placeholder="20/20">
                                @error('visus_od')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Visual Acuity OS -->
                            <div>
                                <label for="visus_os" class="block text-sm font-medium text-gray-900 dark:text-white">
                                    {{ __('his.exam.visus_os') }}
                                </label>
                                <input type="text" name="visus_os" id="visus_os"
                                       value="{{ old('visus_os', $visit->ophthalmicExam?->visus_os) }}"
                                       class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                       placeholder="20/20">
                                @error('visus_os')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- IOP OD -->
                            <div>
                                <label for="iop_od" class="block text-sm font-medium text-gray-900 dark:text-white">
                                    {{ __('his.exam.iop_od') }} (mmHg)
                                </label>
                                <input type="number" step="0.1" name="iop_od" id="iop_od"
                                       value="{{ old('iop_od', $visit->ophthalmicExam?->iop_od) }}"
                                       class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                       placeholder="15.0">
                                @error('iop_od')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- IOP OS -->
                            <div>
                                <label for="iop_os" class="block text-sm font-medium text-gray-900 dark:text-white">
                                    {{ __('his.exam.iop_os') }} (mmHg)
                                </label>
                                <input type="number" step="0.1" name="iop_os" id="iop_os"
                                       value="{{ old('iop_os', $visit->ophthalmicExam?->iop_os) }}"
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
                                    {{ __('his.exam.anterior_segment_findings') }}
                                </label>
                                <textarea name="anterior_segment_findings" id="anterior_segment_findings" rows="4"
                                          class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                          placeholder="Describe anterior segment findings...">{{ old('anterior_segment_findings', $visit->ophthalmicExam?->anterior_segment_findings) }}</textarea>
                                @error('anterior_segment_findings')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Posterior Segment -->
                            <div>
                                <label for="posterior_segment_findings" class="block text-sm font-medium text-gray-900 dark:text-white">
                                    {{ __('his.exam.posterior_segment_findings') }}
                                </label>
                                <textarea name="posterior_segment_findings" id="posterior_segment_findings" rows="4"
                                          class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                          placeholder="Describe posterior segment findings...">{{ old('posterior_segment_findings', $visit->ophthalmicExam?->posterior_segment_findings) }}</textarea>
                                @error('posterior_segment_findings')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-between pt-6">
                            <a href="{{ route('visits.show', $visit) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600">
                                ← {{ __('his.back') }}
                            </a>

                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-600">
                                @if($visit->ophthalmicExam)
                                    {{ __('his.update') }} {{ __('his.workspace.examination') }}
                                @else
                                    {{ __('his.save') }} {{ __('his.workspace.examination') }}
                                @endif
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if($visit->ophthalmicExam)
                <!-- Refractions Section -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 lg:p-8">
                        <div class="flex justify-between items-center mb-6">
                            <h4 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('his.refraction.title') }}</h4>
                            <a href="{{ route('visits.refractions.create', $visit) }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-600">
                                + {{ __('his.refraction.add_refraction') }}
                            </a>
                        </div>

                        @if($visit->ophthalmicExam->refractions->isNotEmpty())
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
                                    @foreach($visit->ophthalmicExam->refractions->sortBy(['eye', 'method']) as $refraction)
                                        <x-table-row>
                                            <x-table-cell header>
                                                <span class="font-medium">{{ $refraction->eye }}</span>
                                            </x-table-cell>

                                            <x-table-cell>
                                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300">
                                                    {{ __("his.refraction_methods.{$refraction->method}") }}
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
                            <x-table-empty
                                colspan="7"
                                title="No refractions recorded"
                                message="Add refraction measurements for this examination."
                                :actionUrl="route('visits.refractions.create', $visit)"
                                actionText="Add First Refraction"
                            />
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
