@php
    $imagingStudies = $visit->imagingStudies;
@endphp

<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ his_trans('workspace.imaging') }}</h3>
        <button onclick="document.getElementById('imaging-modal').classList.remove('hidden')"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-600">
            + {{ his_trans('imaging.add_study') }}
        </button>
    </div>

    @if (session('imaging_success'))
        <div class="rounded-md bg-green-50 dark:bg-green-900/20 p-4">
            <div class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('imaging_success') }}</div>
        </div>
    @endif

    @if($imagingStudies->isNotEmpty())
        <!-- Imaging Studies Table -->
        <x-table>
            <x-slot name="head">
                <x-table-header>Study Type</x-table-header>
                <x-table-header-secondary>Eye</x-table-header-secondary>
                <x-table-header-secondary>Status</x-table-header-secondary>
                <x-table-header-secondary>Ordered By</x-table-header-secondary>
                <x-table-header-secondary>Performed</x-table-header-secondary>
                <x-table-action-header>Actions</x-table-action-header>
            </x-slot>

            <x-slot name="body">
                @foreach($imagingStudies->sortBy('created_at') as $study)
                    <x-table-row>
                        <x-table-cell header>
                            <div>
                                <div class="font-medium text-gray-900 dark:text-white">{{ his_trans("imaging_modalities.{$study->modality}") }}</div>
                                @if($study->findings)
                                    <div class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ Str::limit($study->findings, 50) }}</div>
                                @endif
                            </div>
                        </x-table-cell>

                        <x-table-cell>
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                @if($study->eye === 'OD') bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300
                                @elseif($study->eye === 'OS') bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300
                                @elseif($study->eye === 'OU') bg-purple-100 text-purple-800 dark:bg-purple-900/20 dark:text-purple-300
                                @else bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-300 @endif">
                                {{ his_trans("imaging_eyes.{$study->eye}") }}
                            </span>
                        </x-table-cell>

                        <x-table-cell>
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                @if($study->status === 'ordered') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300
                                @elseif($study->status === 'done') bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300
                                @else bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300 @endif">
                                {{ his_trans("imaging_status.{$study->status}") }}
                            </span>
                        </x-table-cell>

                        <x-table-cell>
                            {{ $study->orderedBy->name ?? '—' }}
                        </x-table-cell>

                        <x-table-cell>
                            @if($study->performed_at)
                                <div>{{ $study->performed_at->format('M d, Y') }}</div>
                                @if($study->performedBy)
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $study->performedBy->name }}</div>
                                @endif
                            @else
                                —
                            @endif
                        </x-table-cell>

                        <x-table-action-cell>
                            <div class="flex space-x-2">
                                <button onclick="editImaging({{ $study->id }})"
                                        class="text-sm font-medium text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                    Edit
                                </button>
                                <form action="{{ route('visits.imaging.destroy', [$visit, $study]) }}" method="POST" class="inline">
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
            colspan="6"
            title="No imaging studies ordered"
            message="Get started by ordering an imaging study for this visit."
        />
    @endif

    <!-- Add/Edit Imaging Study Modal -->
    <div id="imaging-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 id="imaging-modal-title" class="text-lg font-medium text-gray-900 dark:text-white">{{ his_trans('imaging.add_study') }}</h3>
                    <button onclick="closeImagingModal()"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form id="imaging-form" action="{{ route('visits.imaging.store', $visit) }}" method="POST" class="space-y-4">
                    @csrf
                    <div id="imaging-method-field"></div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Modality -->
                        <div>
                            <label for="modality" class="block text-sm font-medium text-gray-900 dark:text-white">{{ his_trans('imaging.modality') }}</label>
                            <select name="modality" id="modality" required
                                    class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:focus:ring-indigo-500">
                                <option value="">Select Modality</option>
                                <option value="OCT">OCT</option>
                                <option value="VF">Visual Field</option>
                                <option value="US">Ultrasound</option>
                                <option value="FA">Fluorescein Angiography</option>
                                <option value="Biometry">Biometry</option>
                                <option value="Photo">Photography</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <!-- Eye -->
                        <div>
                            <label for="imaging_eye" class="block text-sm font-medium text-gray-900 dark:text-white">{{ his_trans('imaging.eye') }}</label>
                            <select name="eye" id="imaging_eye" required
                                    class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:focus:ring-indigo-500">
                                <option value="">Select Eye</option>
                                <option value="OD">OD (Right Eye)</option>
                                <option value="OS">OS (Left Eye)</option>
                                <option value="OU">OU (Both Eyes)</option>
                                <option value="NA">N/A (Not Applicable)</option>
                            </select>
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="imaging_status" class="block text-sm font-medium text-gray-900 dark:text-white">{{ his_trans('imaging.status') }}</label>
                            <select name="status" id="imaging_status" required
                                    class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:focus:ring-indigo-500">
                                <option value="ordered">Ordered</option>
                                <option value="done">Done</option>
                                <option value="reported">Reported</option>
                            </select>
                        </div>

                        <!-- Performed Date -->
                        <div>
                            <label for="performed_at" class="block text-sm font-medium text-gray-900 dark:text-white">{{ his_trans('imaging.performed_at') }}</label>
                            <input type="datetime-local" name="performed_at" id="performed_at"
                                   class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:focus:ring-indigo-500">
                        </div>
                    </div>

                    <!-- Findings -->
                    <div>
                        <label for="findings" class="block text-sm font-medium text-gray-900 dark:text-white">{{ his_trans('imaging.findings') }}</label>
                        <textarea name="findings" id="findings" rows="4"
                                  class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                  placeholder="Enter imaging findings and interpretation..."></textarea>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="closeImagingModal()"
                                class="px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-600">
                            <span id="imaging-submit-text">{{ his_trans('save') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function closeImagingModal() {
    document.getElementById('imaging-modal').classList.add('hidden');
    document.getElementById('imaging-form').reset();
    document.getElementById('imaging-form').action = "{{ route('visits.imaging.store', $visit) }}";
    document.getElementById('imaging-method-field').innerHTML = '';
    document.getElementById('imaging-modal-title').textContent = "{{ his_trans('imaging.add_study') }}";
    document.getElementById('imaging-submit-text').textContent = "{{ his_trans('save') }}";
}

function editImaging(id) {
    // This would be implemented to populate the form with existing data
    // For now, just open the modal - you'd fetch the imaging study data via AJAX
    document.getElementById('imaging-modal').classList.remove('hidden');
    document.getElementById('imaging-modal-title').textContent = "Edit Imaging Study";
    document.getElementById('imaging-submit-text').textContent = "Update";
}
</script>
