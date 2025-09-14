<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('his.visits.title') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="px-4 sm:px-6 lg:px-8">
                <!-- Header Section -->
                <div class="sm:flex sm:items-center">
                    <div class="sm:flex-auto">
                        <h1 class="text-base font-semibold text-gray-900 dark:text-white">{{ __('his.visits.title') }}</h1>
                        <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">Manage patient visits, appointments, and schedules in the system.</p>
                    </div>
                    <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                        <a href="{{ route('visits.create') }}"
                           class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 dark:bg-indigo-500 dark:hover:bg-indigo-400 dark:focus-visible:outline-indigo-500">
                            {{ __('his.visits.add_visit') }}
                        </a>
                    </div>
                </div>

                <!-- Search and Filter -->
                <div class="mt-6">
                    <form method="GET" action="{{ route('visits.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-900 dark:text-white">{{__('his.table_header.search')}}</label>
                            <input type="text"
                                   id="search"
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="Patient, doctor, reason..."
                                   class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-400 dark:focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-900 dark:text-white">{{__('his.table_header.status')}}</label>
                            <select name="status" id="status" class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:focus:ring-indigo-500">
                                <option value="">{{__('his.table_header.all_statuses')}}</option>
                                @foreach(['scheduled', 'arrived', 'in_progress', 'completed', 'cancelled'] as $statusOption)
                                    <option value="{{ $statusOption }}" {{ request('status') === $statusOption ? 'selected' : '' }}>
                                        {{ __("his.visit_status.{$statusOption}") }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="doctor_id" class="block text-sm font-medium text-gray-900 dark:text-white">{{__('his.table_header.doctor')}}</label>
                            <select name="doctor_id" id="doctor_id" class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:focus:ring-indigo-500">
                                <option value="">{{__('his.table_header.all_doctors')}}</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex space-x-2">
                            <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 dark:bg-indigo-500 dark:hover:bg-indigo-400 dark:focus-visible:outline-indigo-500">
                                {{__('his.search_button')}}
                            </button>

                            @if(request()->hasAny(['search', 'status', 'doctor_id', 'date']))
                                <a href="{{ route('visits.index') }}" class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-white/10 dark:text-white dark:ring-white/20 dark:hover:bg-white/20">
                                    {{__('his.clear_button')}}
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Visits Table -->
                <x-table>
                    <x-slot name="head">
                        <x-table-header>{{__('his.table_header.full_name')}}</x-table-header>
                        <x-table-header-secondary>{{__('his.table_header.doctor')}}</x-table-header-secondary>
                        <x-table-header-secondary>{{__('his.table_header.type')}}</x-table-header-secondary>
                        <x-table-header-secondary>{{__('his.table_header.status')}}</x-table-header-secondary>
                        <x-table-header-secondary>{{__('his.table_header.scheduled')}}</x-table-header-secondary>
                        <x-table-header-secondary>{{__('his.table_header.room')}}</x-table-header-secondary>
                        <x-table-action-header>{{__('his.table_header.actions')}}</x-table-action-header>
                    </x-slot>

                    <x-slot name="body">
                        @forelse($visits as $visit)
                            <x-table-row>
                                <x-table-cell header>
                                    <a href="{{ route('visits.show', $visit) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                        {{ $visit->patient->full_name }}
                                    </a>
                                </x-table-cell>

                                <x-table-cell>
                                    {{ $visit->doctor->name }}
                                </x-table-cell>

                                <x-table-cell>
                                    <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium ring-1 ring-inset
                                        @if($visit->type === 'exam') bg-blue-50 text-blue-700 ring-blue-600/20 dark:bg-blue-400/10 dark:text-blue-400 dark:ring-blue-400/30
                                        @elseif($visit->type === 'control') bg-green-50 text-green-700 ring-green-600/20 dark:bg-green-400/10 dark:text-green-400 dark:ring-green-400/30
                                        @else bg-purple-50 text-purple-700 ring-purple-600/20 dark:bg-purple-400/10 dark:text-purple-400 dark:ring-purple-400/30 @endif">
                                        {{ __("his.visit_types.{$visit->type}") }}
                                    </span>
                                </x-table-cell>

                                <x-table-cell>
                                    <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium ring-1 ring-inset
                                        @if($visit->status === 'scheduled') bg-gray-50 text-gray-700 ring-gray-600/20 dark:bg-gray-400/10 dark:text-gray-400 dark:ring-gray-400/30
                                        @elseif($visit->status === 'arrived') bg-yellow-50 text-yellow-800 ring-yellow-600/20 dark:bg-yellow-400/10 dark:text-yellow-400 dark:ring-yellow-400/30
                                        @elseif($visit->status === 'in_progress') bg-blue-50 text-blue-700 ring-blue-600/20 dark:bg-blue-400/10 dark:text-blue-400 dark:ring-blue-400/30
                                        @elseif($visit->status === 'completed') bg-green-50 text-green-700 ring-green-600/20 dark:bg-green-400/10 dark:text-green-400 dark:ring-green-400/30
                                        @else bg-red-50 text-red-700 ring-red-600/20 dark:bg-red-400/10 dark:text-red-400 dark:ring-red-400/30 @endif">
                                        {{ __("his.visit_status.{$visit->status}") }}
                                    </span>
                                </x-table-cell>

                                <x-table-cell>
                                    <div>{{ $visit->scheduled_at->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-400 dark:text-gray-500">{{ $visit->scheduled_at->format('g:i A') }}</div>
                                </x-table-cell>

                                <x-table-cell>
                                    {{ $visit->room ?: '—' }}
                                </x-table-cell>

                                <x-table-action-cell>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('visits.show', $visit) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                            {{__('his.table_cell.view')}}<span class="sr-only">, {{ $visit->patient->full_name }}</span>
                                        </a>
                                        <a href="{{ route('visits.edit', $visit) }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300">
                                            {{__('his.table_cell.edit')}}<span class="sr-only">, {{ $visit->patient->full_name }}</span>
                                        </a>
                                    </div>
                                </x-table-action-cell>
                            </x-table-row>
                        @empty
                            <x-table-empty
                                colspan="7"
                                title="No visits found"
                                message="Get started by scheduling your first visit."
                                action-url="{{ route('visits.create') }}"
                                action-text="{{ __('his.visits.add_visit') }}"
                            />
                        @endforelse
                    </x-slot>
                </x-table>

                <!-- Pagination -->
                @if($visits->hasPages())
                    <div class="mt-6 flex items-center justify-between">
                        <div class="text-sm text-gray-700 dark:text-gray-300">
                            Showing <span class="font-medium text-gray-900 dark:text-white">{{ $visits->firstItem() }}</span> to <span class="font-medium text-gray-900 dark:text-white">{{ $visits->lastItem() }}</span> of <span class="font-medium text-gray-900 dark:text-white">{{ $visits->total() }}</span> results
                        </div>
                        <div>
                            {{ $visits->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div x-data="{ show: true }"
             x-show="show"
             x-transition
             x-init="setTimeout(() => show = false, 3000)"
             class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg z-50">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div x-data="{ show: true }"
             x-show="show"
             x-transition
             x-init="setTimeout(() => show = false, 5000)"
             class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg z-50">
            {{ session('error') }}
        </div>
    @endif
</x-app-layout>
