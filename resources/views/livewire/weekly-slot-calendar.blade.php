<div>
    <!-- Header with Navigation -->
    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-4">
                    <button wire:click="previousWeek" class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>

                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                        {{ \Carbon\Carbon::parse($weekStartDate)->format('M j') }} -
                        {{ \Carbon\Carbon::parse($weekStartDate)->endOfWeek()->format('M j, Y') }}
                    </h2>

                    <button wire:click="nextWeek" class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>

                    <button wire:click="goToToday" class="px-3 py-1 text-sm bg-indigo-100 text-indigo-700 rounded-md hover:bg-indigo-200 dark:bg-indigo-900 dark:text-indigo-300 dark:hover:bg-indigo-800">
                        {{ __('slots.today') }}
                    </button>
                </div>

                <!-- Doctor Filter (if admin/receptionist) -->
                @if($this->doctors->count() > 0)
                <div>
                    <select wire:model.live="doctorId" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm dark:bg-gray-700 dark:text-white dark:ring-gray-600">
                        <option value="">{{ __('common.table_header.all_doctors') }}</option>
                        @foreach($this->doctors as $doctor)
                            <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-4 text-center">

        {{-- @dd($this->slots) --}}
    @forelse ($this->slots as $date => $timeSlots)
    <div class="mb-4 border-b border-gray-200 dark:border-gray-700 pb-4 flex flex-col gap-2 bg-white dark:bg-gray-800 rounded-md p-4">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($date)->format('D j') }}</h3>
        <div class="gap-2 grid grid-cols-2">
            @foreach($timeSlots as $time => $timeSlot)
            <div wire:click="handleSlotClick({{ $timeSlot[0]->id }})" class="border border-gray-200 dark:border-gray-700 p-2 bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                <h1 class="text-gray-500 dark:text-gray-400">{{ $time }}</h1>
            </div>
            @endforeach
        </div>
    </div>
    @empty
        <div class="text-center text-gray-500 dark:text-gray-400">{{ __('slots.no_slots') }}</div>
    @endforelse

    @if($selectedSlot)
    <x-confirmation-modal wire:model.live="showSlotModal">
        <x-slot name="title">
            {{ __('slots.modal.title') }}
        </x-slot>

        <x-slot name="content">
            {{ __('slots.modal.content') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeModal" wire:loading.attr="disabled">
                {{ __('common.cancel') }}
            </x-secondary-button>
            @if($selectedSlot->status == 'available')
                <x-warning-button class="ms-3" wire:click="blockSlot({{ $selectedSlot->id }})" wire:loading.attr="disabled">
                    {{ __('slots.block') }}
                </x-warning-button>
            @else
                <x-danger-button class="ms-3" wire:click="unblockSlot({{ $selectedSlot->id }})" wire:loading.attr="disabled">
                    {{ __('slots.unblock') }}
                </x-danger-button>
            @endif
            <x-danger-button class="ms-3" wire:click="deleteSlot({{ $selectedSlot->id }})" wire:loading.attr="disabled">
                {{ __('common.delete') }}
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>
    @endif
</div>
