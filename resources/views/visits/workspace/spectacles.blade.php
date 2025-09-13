@php
    $spectacles = $visit->spectaclePrescriptions;
@endphp

<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ his_trans('workspace.spectacles') }}</h3>
        <button onclick="document.getElementById('spectacles-modal').classList.remove('hidden')"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-600">
            + {{ his_trans('spectacles.add_prescription') }}
        </button>
    </div>

    @if (session('spectacles_success'))
        <div class="rounded-md bg-green-50 dark:bg-green-900/20 p-4">
            <div class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('spectacles_success') }}</div>
        </div>
    @endif

    @if($spectacles->isNotEmpty())
        @foreach($spectacles as $prescription)
            <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h4 class="text-md font-medium text-gray-900 dark:text-white">
                            {{ his_trans("spectacle_types.{$prescription->type}") }} Prescription
                        </h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Prescribed by {{ $prescription->doctor->name }} on {{ $prescription->created_at->format('M d, Y') }}
                        </p>
                        @if($prescription->valid_until)
                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                Valid until: {{ $prescription->valid_until->format('M d, Y') }}
                            </p>
                        @endif
                    </div>
                    <div class="flex space-x-2">
                        <button onclick="editSpectacles({{ $prescription->id }})"
                                class="text-sm font-medium text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                            Edit
                        </button>
                        <form action="{{ route('visits.spectacles.destroy', [$visit, $prescription]) }}" method="POST" class="inline">
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

                <!-- Prescription Details -->
                <div class="space-y-6">
                    <!-- Eye Prescriptions Table -->
                    <div class="overflow-hidden border border-gray-200 dark:border-gray-700 sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Eye</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Sphere</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Cylinder</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Axis</th>
                                    @if($prescription->type === 'bifocal' || $prescription->type === 'progressive')
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Add</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                <!-- OD (Right Eye) -->
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">OD</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $prescription->od_sphere ? number_format($prescription->od_sphere, 2) : '—' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $prescription->od_cylinder ? number_format($prescription->od_cylinder, 2) : '—' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $prescription->od_axis ?? '—' }}°
                                    </td>
                                    @if($prescription->type === 'bifocal' || $prescription->type === 'progressive')
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $prescription->od_add ? number_format($prescription->od_add, 2) : '—' }}
                                        </td>
                                    @endif
                                </tr>

                                <!-- OS (Left Eye) -->
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">OS</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $prescription->os_sphere ? number_format($prescription->os_sphere, 2) : '—' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $prescription->os_cylinder ? number_format($prescription->os_cylinder, 2) : '—' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $prescription->os_axis ?? '—' }}°
                                    </td>
                                    @if($prescription->type === 'bifocal' || $prescription->type === 'progressive')
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $prescription->os_add ? number_format($prescription->os_add, 2) : '—' }}
                                        </td>
                                    @endif
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Additional Details -->
                    @if($prescription->pd_distance || $prescription->pd_near || $prescription->notes)
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @if($prescription->pd_distance)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">PD Distance</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $prescription->pd_distance }}mm</dd>
                                </div>
                            @endif

                            @if($prescription->pd_near)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">PD Near</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $prescription->pd_near }}mm</dd>
                                </div>
                            @endif

                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Type</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                        @if($prescription->type === 'distance') bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300
                                        @elseif($prescription->type === 'near') bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300
                                        @elseif($prescription->type === 'bifocal') bg-purple-100 text-purple-800 dark:bg-purple-900/20 dark:text-purple-300
                                        @else bg-orange-100 text-orange-800 dark:bg-orange-900/20 dark:text-orange-300 @endif">
                                        {{ his_trans("spectacle_types.{$prescription->type}") }}
                                    </span>
                                </dd>
                            </div>
                        </div>
                    @endif

                    @if($prescription->notes)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Notes</dt>
                            <dd class="mt-2 text-sm text-gray-900 dark:text-white whitespace-pre-wrap">{{ $prescription->notes }}</dd>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    @else
        <div class="text-center py-12 text-gray-500 dark:text-gray-400">
            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No spectacle prescriptions</h3>
            <p class="text-gray-500 dark:text-gray-400 mb-6">Get started by creating a spectacle prescription for this visit.</p>
        </div>
    @endif

    <!-- Add Spectacle Prescription Modal -->
    <div id="spectacles-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-4/5 lg:w-3/5 shadow-lg rounded-md bg-white dark:bg-gray-800 max-h-screen overflow-y-auto">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ his_trans('spectacles.add_prescription') }}</h3>
                    <button onclick="closeSpectaclesModal()"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form action="{{ route('visits.spectacles.store', $visit) }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Prescription Type and Validity -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="spectacle_type" class="block text-sm font-medium text-gray-900 dark:text-white">{{ his_trans('spectacles.type') }}</label>
                            <select name="type" id="spectacle_type" required
                                    class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:focus:ring-indigo-500"
                                    onchange="toggleAddFields()">
                                <option value="distance">Distance</option>
                                <option value="near">Near</option>
                                <option value="bifocal">Bifocal</option>
                                <option value="progressive">Progressive</option>
                            </select>
                        </div>

                        <div>
                            <label for="valid_until" class="block text-sm font-medium text-gray-900 dark:text-white">{{ his_trans('spectacles.valid_until') }}</label>
                            <input type="date" name="valid_until" id="valid_until"
                                   class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:focus:ring-indigo-500">
                        </div>
                    </div>

                    <!-- Right Eye (OD) -->
                    <div class="bg-gray-50 dark:bg-gray-900/20 p-4 rounded-lg">
                        <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">OD (Right Eye)</h4>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="od_sphere" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sphere</label>
                                <input type="number" step="0.25" name="od_sphere" id="od_sphere"
                                       class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                       placeholder="0.00">
                            </div>
                            <div>
                                <label for="od_cylinder" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cylinder</label>
                                <input type="number" step="0.25" name="od_cylinder" id="od_cylinder"
                                       class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                       placeholder="0.00">
                            </div>
                            <div>
                                <label for="od_axis" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Axis</label>
                                <input type="number" min="1" max="180" name="od_axis" id="od_axis"
                                       class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                       placeholder="90">
                            </div>
                            <div id="od_add_field" style="display: none;">
                                <label for="od_add" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Add</label>
                                <input type="number" step="0.25" name="od_add" id="od_add"
                                       class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                       placeholder="0.00">
                            </div>
                        </div>
                    </div>

                    <!-- Left Eye (OS) -->
                    <div class="bg-gray-50 dark:bg-gray-900/20 p-4 rounded-lg">
                        <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">OS (Left Eye)</h4>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="os_sphere" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sphere</label>
                                <input type="number" step="0.25" name="os_sphere" id="os_sphere"
                                       class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                       placeholder="0.00">
                            </div>
                            <div>
                                <label for="os_cylinder" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cylinder</label>
                                <input type="number" step="0.25" name="os_cylinder" id="os_cylinder"
                                       class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                       placeholder="0.00">
                            </div>
                            <div>
                                <label for="os_axis" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Axis</label>
                                <input type="number" min="1" max="180" name="os_axis" id="os_axis"
                                       class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                       placeholder="90">
                            </div>
                            <div id="os_add_field" style="display: none;">
                                <label for="os_add" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Add</label>
                                <input type="number" step="0.25" name="os_add" id="os_add"
                                       class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                       placeholder="0.00">
                            </div>
                        </div>
                    </div>

                    <!-- PD and Notes -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="pd_distance" class="block text-sm font-medium text-gray-900 dark:text-white">PD Distance (mm)</label>
                            <input type="number" step="0.5" name="pd_distance" id="pd_distance"
                                   class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                   placeholder="63.0">
                        </div>
                        <div>
                            <label for="pd_near" class="block text-sm font-medium text-gray-900 dark:text-white">PD Near (mm)</label>
                            <input type="number" step="0.5" name="pd_near" id="pd_near"
                                   class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                   placeholder="60.0">
                        </div>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="spectacle_notes" class="block text-sm font-medium text-gray-900 dark:text-white">{{ his_trans('spectacles.notes') }}</label>
                        <textarea name="notes" id="spectacle_notes" rows="3"
                                  class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                  placeholder="Additional notes for the optician..."></textarea>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="closeSpectaclesModal()"
                                class="px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-600">
                            Save Prescription
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function closeSpectaclesModal() {
    document.getElementById('spectacles-modal').classList.add('hidden');
    document.querySelector('#spectacles-modal form').reset();
    toggleAddFields(); // Reset add fields visibility
}

function toggleAddFields() {
    const type = document.getElementById('spectacle_type').value;
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

function editSpectacles(id) {
    document.getElementById('spectacles-modal').classList.remove('hidden');
}

// Initialize add fields visibility on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleAddFields();
});
</script>
