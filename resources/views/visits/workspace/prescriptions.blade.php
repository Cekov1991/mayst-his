<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('his.workspace.prescriptions') }} - {{ $visit->patient->full_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Workspace Navigation -->
            @include('visits.workspace.partials.navigation', ['visit' => $visit, 'active' => 'prescriptions'])

            <!-- Prescriptions -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('his.prescription.prescriptions') }}</h3>
                        <a href="{{ route('visits.prescriptions.create', $visit) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-600">
                            + {{ __('his.prescription.add_prescription') }}
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="rounded-md bg-green-50 dark:bg-green-900/20 p-4 mb-6">
                            <div class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}</div>
                        </div>
                    @endif

                    @if($visit->prescriptions->isNotEmpty())
                        <div class="space-y-6">
                            @foreach($visit->prescriptions->sortByDesc('created_at') as $prescription)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-6">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <div class="flex items-center space-x-3">
                                                <h4 class="text-md font-medium text-gray-900 dark:text-white">
                                                    {{ __('his.prescription.prescription') }} #{{ $prescription->id }}
                                                </h4>
                                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                                    by {{ $prescription->doctor->name }}
                                                </span>
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                Created: {{ $prescription->created_at->format('M d, Y g:i A') }}
                                            </div>
                                            @if($prescription->notes)
                                                <div class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                                    <strong>Notes:</strong> {{ $prescription->notes }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('visits.prescriptions.edit', [$visit, $prescription]) }}"
                                               class="text-sm font-medium text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                Edit
                                            </a>
                                            <form action="{{ route('visits.prescriptions.destroy', [$visit, $prescription]) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        onclick="return confirm('Are you sure?')"
                                                        class="text-sm font-medium text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Prescription Items -->
                                    @if($prescription->prescriptionItems->isNotEmpty())
                                        <x-table>
                                            <x-slot name="head">
                                                <x-table-header>Medication</x-table-header>
                                                <x-table-header-secondary>Form</x-table-header-secondary>
                                                <x-table-header-secondary>Strength</x-table-header-secondary>
                                                <x-table-header-secondary>Instructions</x-table-header-secondary>
                                                <x-table-header-secondary>Duration</x-table-header-secondary>
                                                <x-table-header-secondary>Repeats</x-table-header-secondary>
                                            </x-slot>

                                            <x-slot name="body">
                                                @foreach($prescription->prescriptionItems as $item)
                                                    <x-table-row>
                                                        <x-table-cell header>
                                                            <span class="font-medium">{{ $item->drug_name }}</span>
                                                        </x-table-cell>

                                                        <x-table-cell>
                                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300">
                                                                {{ __("his.drug_forms.{$item->form}") }}
                                                            </span>
                                                        </x-table-cell>

                                                        <x-table-cell>{{ $item->strength ?? '—' }}</x-table-cell>
                                                        <x-table-cell>{{ $item->dosage_instructions }}</x-table-cell>
                                                        <x-table-cell>{{ $item->duration_days ? $item->duration_days . ' days' : '—' }}</x-table-cell>
                                                        <x-table-cell>{{ $item->repeats ?? '—' }}</x-table-cell>
                                                    </x-table-row>
                                                @endforeach
                                            </x-slot>
                                        </x-table>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <x-table-empty
                            title="No prescriptions created"
                            message="Create prescriptions with medications for this visit."
                            :actionUrl="route('visits.prescriptions.create', $visit)"
                            actionText="Create First Prescription"
                        />
                    @endif

                    <!-- Back Button -->
                    <div class="flex justify-between mt-8">
                        <a href="{{ route('visits.show', $visit) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600">
                            ← {{ __('his.back') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
