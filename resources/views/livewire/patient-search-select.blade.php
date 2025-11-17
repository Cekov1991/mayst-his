<div class="relative">
    <x-label for="patient_search" :value="$label ?? __('visits.patient')" />

    <!-- Hidden input for form submission -->
    <input type="hidden" name="patient_id" value="{{ $selectedPatientId }}" required>

    <!-- Search Input -->
    <div class="relative mt-1">
        <input
            type="text"
            id="patient_search"
            wire:model.live.debounce.300ms="search"
            placeholder="{{ $placeholder ?? __('visits.patient') }}"
            class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-900 dark:border-gray-600 dark:text-white"
            required
        />

        <!-- Clear Button -->
        @if($selectedPatientId)
            <button
                type="button"
                wire:click="clearSelection"
                class="absolute right-2 top-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        @endif

        <!-- Loading Indicator -->
        <div wire:loading class="absolute right-2 top-2">
            <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    </div>

    <!-- Results Dropdown -->
    @if(strlen($search) >= 2)
        <div class="absolute z-50 mt-1 w-full bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-300 rounded-md shadow-lg max-h-60 overflow-auto">
            @if($this->patients->isEmpty() && !$selectedPatientId)
                <div class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('No patients found') }}
                </div>
            @else
                @foreach($this->patients as $patient)
                    <div
                        wire:click="selectPatient({{ $patient->id }})"
                        class="px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer"
                    >
                        <div class="font-medium text-gray-900 dark:text-gray-100">
                            {{ $patient->full_name }}
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $patient->unique_master_citizen_number ?: $patient->dob->format('M d, Y') }}
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    @endif

    <!-- Selected Patient Display -->
    @if($selectedPatient)
        <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            Selected: {{ $selectedPatient->full_name }} - {{ $selectedPatient->dob->format('M d, Y') }}
        </div>
    @endif
</div>

