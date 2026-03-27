<div class="relative">
    <x-label for="slot_date" :value="$label" />

    <!-- Hidden inputs for form submission -->
    <input type="hidden" name="slot_id" value="{{ $selectedSlotId }}" id="slot_id_input">
    <input type="hidden" name="scheduled_at" value="{{ $selectedSlot ? $selectedSlot->start_time->format('Y-m-d\TH:i') : '' }}" id="scheduled_at_input">

    <!-- Date Input with Slot Display -->
    <div class="relative mt-1">
        <input
            type="date"
            id="slot_date"
            wire:model.live="selectedDate"
            wire:click="toggleSlots"
            class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-900 dark:border-gray-600 dark:text-white pr-10"
            required
        />

        <!-- Clear Button -->
        @if($selectedSlotId)
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
        <div wire:loading class="absolute right-8 top-2">
            <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    </div>

    <!-- Available Slots Dropdown -->
    @if($showSlots && $doctorId && $selectedDate)
        <div class="absolute z-50 mt-1 w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-lg max-h-60 overflow-auto">
            @if($this->availableSlots->isEmpty())
                <div class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('slots.no_slots') }} {{ __('for this date') }}
                </div>
            @else
                <div class="px-4 py-2 text-xs text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                    {{ __('slots.available_slots') }} ({{ $this->availableSlots->count() }})
                </div>
                @foreach($this->availableSlots as $slot)
                    <div
                        wire:click="selectSlot({{ $slot->id }})"
                        class="px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer border-b border-gray-100 dark:border-gray-700 last:border-b-0"
                    >
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="font-medium text-gray-900 dark:text-gray-100">
                                    {{ $slot->start_time->format('H:i') }} - {{ $slot->end_time->format('H:i') }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $slot->start_time->format('l, F j, Y') }}
                                </div>
                            </div>
                            <div class="flex items-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    {{ __('slots.statuses.available') }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    @endif

    <!-- Selected Slot Confirmation -->
    @if($selectedSlot)
    <div class="px-4 py-3 shadow">
        <div class="flex justify-between items-center">
            <div>
                <div class="font-medium text-gray-900 dark:text-gray-100">
                    {{ $selectedSlot->start_time->format('H:i') }} - {{ $selectedSlot->end_time->format('H:i') }}
                </div>
                <div class="text-xs text-gray-500 dark:text-gray-400">
                    {{ $selectedSlot->start_time->format('l, F j, Y') }}
                </div>
            </div>
            <div class="flex items-center">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                    {{ __('slots.statuses.selected') }}
                </span>
            </div>
        </div>
    </div>
    @endif
    <!-- Doctor Required Message -->
    @if(!$doctorId && $showSlots)
        <div class="mt-2 text-sm text-red-600 dark:text-red-400">
            {{ __('Please select a doctor first') }}
        </div>
    @endif
</div>

<script>
    document.addEventListener('livewire:init', function () {
        Livewire.on('slotSelected', function (data) {
            // Update hidden form inputs
            document.getElementById('slot_id_input').value = data[0].slotId || '';
            document.getElementById('scheduled_at_input').value = data[0].scheduledAt || '';
        });
    });
</script>
