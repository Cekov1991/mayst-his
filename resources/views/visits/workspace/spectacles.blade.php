<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('workspace.spectacles') }} - {{ $visit->patient->full_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Workspace Navigation -->
            @include('visits.workspace.partials.navigation', ['visit' => $visit, 'active' => 'spectacles'])

            <!-- Spectacle Prescriptions -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('spectacles.title') }}</h3>
                        <a href="{{ route('visits.spectacles.create', $visit) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-600">
                            + {{ __('spectacles.add_prescription') }}
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="rounded-md bg-green-50 dark:bg-green-900/20 p-4 mb-6">
                            <div class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}</div>
                        </div>
                    @endif

                    @if($visit->spectaclePrescriptions->isNotEmpty())
                        <div class="space-y-6">
                            @foreach($visit->spectaclePrescriptions->sortByDesc('created_at') as $prescription)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-6">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <div class="flex items-center space-x-3">
                                                <h4 class="text-md font-medium text-gray-900 dark:text-white">
                                                    {{ __('spectacles.prescription') }} #{{ $prescription->id }}
                                                </h4>
                                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                                    @if($prescription->type === 'distance') bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300
                                                    @elseif($prescription->type === 'near') bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300
                                                    @elseif($prescription->type === 'bifocal') bg-purple-100 text-purple-800 dark:bg-purple-900/20 dark:text-purple-300
                                                    @else bg-indigo-100 text-indigo-800 dark:bg-indigo-900/20 dark:text-indigo-300 @endif">
                                                    {{ __("spectacles.types.{$prescription->type}") }}
                                                </span>
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                Prescribed by {{ $prescription->doctor->name }} on {{ $prescription->created_at->format('M d, Y g:i A') }}
                                            </div>
                                            @if($prescription->valid_until)
                                                <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                                    Valid until: {{ $prescription->valid_until->format('M d, Y') }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('visits.spectacles.edit', [$visit, $prescription]) }}"
                                               class="text-sm font-medium text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                Edit
                                            </a>
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
                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                        <!-- Right Eye (OD) -->
                                        <div>
                                            <h5 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Right Eye (OD)</h5>
                                            <div class="bg-gray-50 dark:bg-gray-900/20 rounded-lg p-4 space-y-2">
                                                <div class="grid grid-cols-2 gap-4 text-sm">
                                                    <div>
                                                        <span class="text-gray-500 dark:text-gray-400">Sphere:</span>
                                                        <span class="font-mono">{{ $prescription->od_sphere ? sprintf('%+.2f', $prescription->od_sphere) : '—' }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500 dark:text-gray-400">Cylinder:</span>
                                                        <span class="font-mono">{{ $prescription->od_cylinder ? sprintf('%+.2f', $prescription->od_cylinder) : '—' }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500 dark:text-gray-400">Axis:</span>
                                                        <span class="font-mono">{{ $prescription->od_axis ?? '—' }}°</span>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500 dark:text-gray-400">Add:</span>
                                                        <span class="font-mono">{{ $prescription->od_add ? sprintf('%+.2f', $prescription->od_add) : '—' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Left Eye (OS) -->
                                        <div>
                                            <h5 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Left Eye (OS)</h5>
                                            <div class="bg-gray-50 dark:bg-gray-900/20 rounded-lg p-4 space-y-2">
                                                <div class="grid grid-cols-2 gap-4 text-sm">
                                                    <div>
                                                        <span class="text-gray-500 dark:text-gray-400">Sphere:</span>
                                                        <span class="font-mono">{{ $prescription->os_sphere ? sprintf('%+.2f', $prescription->os_sphere) : '—' }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500 dark:text-gray-400">Cylinder:</span>
                                                        <span class="font-mono">{{ $prescription->os_cylinder ? sprintf('%+.2f', $prescription->os_cylinder) : '—' }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500 dark:text-gray-400">Axis:</span>
                                                        <span class="font-mono">{{ $prescription->os_axis ?? '—' }}°</span>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500 dark:text-gray-400">Add:</span>
                                                        <span class="font-mono">{{ $prescription->os_add ? sprintf('%+.2f', $prescription->os_add) : '—' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Additional Details -->
                                        <div class="lg:col-span-2">
                                            <div class="bg-gray-50 dark:bg-gray-900/20 rounded-lg p-4">
                                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                                                    <div>
                                                        <span class="text-gray-500 dark:text-gray-400">PD Distance:</span>
                                                        <span class="font-mono">{{ $prescription->pd_distance ? $prescription->pd_distance . ' mm' : '—' }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500 dark:text-gray-400">PD Near:</span>
                                                        <span class="font-mono">{{ $prescription->pd_near ? $prescription->pd_near . ' mm' : '—' }}</span>
                                                    </div>
                                                    @if($prescription->notes)
                                                        <div class="md:col-span-2 lg:col-span-1">
                                                            <span class="text-gray-500 dark:text-gray-400">Notes:</span>
                                                            <div class="text-gray-900 dark:text-white mt-1">{{ $prescription->notes }}</div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <x-table-empty
                            :title="__('common.table_empty.title')"
                            :message="__('common.table_empty.message')"
                            :actionUrl="route('visits.spectacles.create', $visit)"
                            :actionText="__('common.table_empty.action_text')"
                        />
                    @endif

                    <!-- Back Button -->
                    <div class="flex justify-between mt-8">
                        <a href="{{ route('visits.show', $visit) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600">
                            ← {{ __('common.back') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
