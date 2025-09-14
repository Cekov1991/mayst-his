<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('his.patients.title') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="px-4 sm:px-6 lg:px-8">
                <!-- Header Section -->
                <div class="sm:flex sm:items-center">
                    <div class="sm:flex-auto">
                        <h1 class="text-base font-semibold text-gray-900 dark:text-white">{{ __('his.patients.title') }}</h1>
                        <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">A complete list of all patients in the system including their personal information and contact details.</p>
                    </div>
                    <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                        <a href="{{ route('patients.create') }}"
                           class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 dark:bg-indigo-500 dark:hover:bg-indigo-400 dark:focus-visible:outline-indigo-500">
                            {{__('his.patients.add_patient')}}
                        </a>
                    </div>
                </div>

                <!-- Search and Filter -->
                <div class="mt-6">
                    <form method="GET" action="{{ route('patients.index') }}" class="flex flex-wrap items-center gap-2">
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Search patients..."
                               class="block w-80 rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-400 dark:focus:ring-indigo-500">

                        <select name="sex" class="block rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:focus:ring-indigo-500">
                            <option value="">All</option>
                            @foreach(['male', 'female', 'other', 'unknown'] as $sexOption)
                                <option value="{{ $sexOption }}" {{ request('sex') === $sexOption ? 'selected' : '' }}>
                                    {{ __("his.sex_options.{$sexOption}") }}
                                </option>
                            @endforeach
                        </select>

                        <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 dark:bg-indigo-500 dark:hover:bg-indigo-400 dark:focus-visible:outline-indigo-500">
                            {{__('his.search_button')}}
                        </button>

                        @if(request()->hasAny(['search', 'sex']))
                            <a href="{{ route('patients.index') }}" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                                {{__('his.clear_button')}}
                            </a>
                        @endif
                    </form>
                </div>

                <!-- Patients Table -->
                <x-table>
                    <x-slot name="head">
                        <x-table-header>{{__('his.table_header.full_name')}}</x-table-header>
                        <x-table-header-secondary>{{__('his.table_header.sex')}}</x-table-header-secondary>
                        <x-table-header-secondary>{{__('his.table_header.dob')}}</x-table-header-secondary>
                        <x-table-header-secondary>{{__('his.table_header.phone')}}</x-table-header-secondary>
                        <x-table-header-secondary>{{__('his.table_header.unique_master_citizen_number')}}</x-table-header-secondary>
                        <x-table-action-header>{{__('his.table_header.actions')}}</x-table-action-header>
                    </x-slot>

                    <x-slot name="body">
                        @forelse($patients as $patient)
                            <x-table-row>
                                <x-table-cell header>
                                    <a href="{{ route('patients.show', $patient) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                        {{ $patient->full_name }}
                                    </a>
                                </x-table-cell>

                                <x-table-cell>
                                    {{ __("his.sex_options.{$patient->sex}") }}
                                </x-table-cell>

                                <x-table-cell>
                                    <div>{{ $patient->dob->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-400 dark:text-gray-500">{{__('his.patients.age')}} {{ $patient->dob->age }}</div>
                                </x-table-cell>

                                <x-table-cell>
                                    @if($patient->phone)
                                        <a href="tel:{{ $patient->phone }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                            {{ $patient->phone }}
                                        </a>
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500">—</span>
                                    @endif
                                </x-table-cell>

                                <x-table-cell>
                                    {{ $patient->unique_master_citizen_number ?: '—' }}
                                </x-table-cell>

                                <x-table-action-cell>
                                    <a href="{{ route('patients.edit', $patient) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                        {{__('his.table_cell.edit_patient')}}<span class="sr-only">, {{ $patient->full_name }}</span>
                                    </a>
                                </x-table-action-cell>
                            </x-table-row>
                        @empty
                            <x-table-empty
                                colspan="6"
                                title="No patients found"
                                message="Get started by adding your first patient to the system."
                                action-url="{{ route('patients.create') }}"
                                action-text="{{__('his.patients.add_patient')}}"
                            />
                        @endforelse
                    </x-slot>
                </x-table>

                <!-- Pagination -->
                @if($patients->hasPages())
                    <div class="mt-6 flex items-center justify-between">
                        <div class="text-sm text-gray-700 dark:text-gray-300">
                            {{__('his.patients.showing')}} <span class="font-medium text-gray-900 dark:text-white">{{ $patients->firstItem() }}</span> {{__('his.patients.to')}} <span class="font-medium text-gray-900 dark:text-white">{{ $patients->lastItem() }}</span> {{__('his.patients.of')}} <span class="font-medium text-gray-900 dark:text-white">{{ $patients->total() }}</span> {{__('his.patients.results')}}
                        </div>
                        <div>
                            {{ $patients->links() }}
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
