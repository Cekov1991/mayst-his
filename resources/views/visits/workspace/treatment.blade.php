@php
    $treatments = $visit->treatmentPlans;
@endphp

<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ his_trans('workspace.treatment') }}</h3>
        <button onclick="document.getElementById('treatment-modal').classList.remove('hidden')"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-600">
            + {{ his_trans('treatment.add_treatment') }}
        </button>
    </div>

    @if (session('treatment_success'))
        <div class="rounded-md bg-green-50 dark:bg-green-900/20 p-4">
            <div class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('treatment_success') }}</div>
        </div>
    @endif

    @if($treatments->isNotEmpty())
        <!-- Treatment Plans Table -->
        <x-table>
            <x-slot name="head">
                <x-table-header>Treatment Type</x-table-header>
                <x-table-header-secondary>Recommendation</x-table-header-secondary>
                <x-table-header-secondary>Status</x-table-header-secondary>
                <x-table-header-secondary>Planned Date</x-table-header-secondary>
                <x-table-action-header>Actions</x-table-action-header>
            </x-slot>

            <x-slot name="body">
                @foreach($treatments->sortBy('created_at') as $treatment)
                    <x-table-row>
                        <x-table-cell header>
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                @if($treatment->plan_type === 'surgery') bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300
                                @elseif($treatment->plan_type === 'injection') bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300
                                @elseif($treatment->plan_type === 'medical') bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300
                                @else bg-purple-100 text-purple-800 dark:bg-purple-900/20 dark:text-purple-300 @endif">
                                {{ his_trans("treatment_types.{$treatment->plan_type}") }}
                            </span>
                        </x-table-cell>

                        <x-table-cell>
                            <div>
                                <div class="font-medium text-gray-900 dark:text-white">{{ $treatment->recommendation }}</div>
                                @if($treatment->details)
                                    <div class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ Str::limit($treatment->details, 60) }}</div>
                                @endif
                            </div>
                        </x-table-cell>

                        <x-table-cell>
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                @if($treatment->status === 'proposed') bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-300
                                @elseif($treatment->status === 'accepted') bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300
                                @elseif($treatment->status === 'scheduled') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300
                                @elseif($treatment->status === 'done') bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300
                                @else bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300 @endif">
                                {{ his_trans("treatment_status.{$treatment->status}") }}
                            </span>
                        </x-table-cell>

                        <x-table-cell>
                            {{ $treatment->planned_date ? $treatment->planned_date->format('M d, Y') : '—' }}
                        </x-table-cell>

                        <x-table-action-cell>
                            <div class="flex space-x-2">
                                <button onclick="editTreatment({{ $treatment->id }})"
                                        class="text-sm font-medium text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                    Edit
                                </button>
                                <form action="{{ route('visits.treatments.destroy', [$visit, $treatment]) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            onclick="return confirm('Are you sure?')"
                                            class="text-sm font-medium text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </x-table-action-cell>
                    </x-table-row>
                @endforeach
            </x-slot>
        </x-table>
    @else
        <x-table-empty
            colspan="5"
            title="No treatment plans"
            message="Get started by creating a treatment plan for this visit."
        />
    @endif

    <!-- Add Treatment Plan Modal -->
    <div id="treatment-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ his_trans('treatment.add_treatment') }}</h3>
                    <button onclick="closeTreatmentModal()"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form action="{{ route('visits.treatments.store', $visit) }}" method="POST" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Plan Type -->
                        <div>
                            <label for="plan_type" class="block text-sm font-medium text-gray-900 dark:text-white">{{ his_trans('treatment.plan_type') }}</label>
                            <select name="plan_type" id="plan_type" required
                                    class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:focus:ring-indigo-500">
                                <option value="">Select Type</option>
                                <option value="surgery">Surgery</option>
                                <option value="injection">Injection</option>
                                <option value="medical">Medical Treatment</option>
                                <option value="advice">Advice</option>
                            </select>
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="treatment_status" class="block text-sm font-medium text-gray-900 dark:text-white">{{ his_trans('treatment.status') }}</label>
                            <select name="status" id="treatment_status" required
                                    class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:focus:ring-indigo-500">
                                <option value="proposed">Proposed</option>
                                <option value="accepted">Accepted</option>
                                <option value="scheduled">Scheduled</option>
                                <option value="done">Done</option>
                                <option value="declined">Declined</option>
                            </select>
                        </div>
                    </div>

                    <!-- Recommendation -->
                    <div>
                        <label for="recommendation" class="block text-sm font-medium text-gray-900 dark:text-white">{{ his_trans('treatment.recommendation') }}</label>
                        <input type="text" name="recommendation" id="recommendation" required
                               class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                               placeholder="Brief treatment recommendation...">
                    </div>

                    <!-- Details -->
                    <div>
                        <label for="treatment_details" class="block text-sm font-medium text-gray-900 dark:text-white">{{ his_trans('treatment.details') }}</label>
                        <textarea name="details" id="treatment_details" rows="3"
                                  class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                  placeholder="Detailed treatment plan and instructions..."></textarea>
                    </div>

                    <!-- Planned Date -->
                    <div>
                        <label for="planned_date" class="block text-sm font-medium text-gray-900 dark:text-white">{{ his_trans('treatment.planned_date') }}</label>
                        <input type="date" name="planned_date" id="planned_date"
                               class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:focus:ring-indigo-500">
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="closeTreatmentModal()"
                                class="px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-600">
                            Save Treatment Plan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function closeTreatmentModal() {
    document.getElementById('treatment-modal').classList.add('hidden');
    document.querySelector('#treatment-modal form').reset();
}

function editTreatment(id) {
    // For now, just open the modal - you'd fetch the treatment data via AJAX
    document.getElementById('treatment-modal').classList.remove('hidden');
}
</script>
