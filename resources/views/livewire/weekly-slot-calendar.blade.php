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

    @forelse ($this->slots as $date => $timeSlots)
    <div class="shadow-md mb-4 border-b border-gray-200 dark:border-gray-700 pb-4 flex flex-col gap-2 bg-white dark:bg-gray-800 rounded-md p-4">
        <div class="flex flex-col items-center justify-center">
            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 10h16m-8-3V4M7 7V4m10 3V4M5 20h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Zm3-7h.01v.01H8V13Zm4 0h.01v.01H12V13Zm4 0h.01v.01H16V13Zm-8 4h.01v.01H8V17Zm4 0h.01v.01H12V17Zm4 0h.01v.01H16V17Z"/>
            </svg>

            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($date)->format('D j') }}</h3>
        </div>
        <div class="gap-2 grid grid-cols-2">
            @foreach($timeSlots as $time => $timeSlot)

            <div class="
            {{ $timeSlot[0]->status == 'blocked' ? 'border-yellow-500 text-yellow-500' : 'text-gray-500 dark:text-gray-400' }}
            {{ $timeSlot[0]->status == 'booked' ? 'border-green-500 text-green-500' : '' }}
            border rounded-md border-gray-200 dark:border-gray-700 p-2 bg-white dark:bg-gray-800">
                <div class="">
                    <h1 class="text-xl font-bold">{{ $time }}</h1>
                </div>
                <div class="text-center flex items-center justify-between border-t border-gray-200 pt-2">
                    @if($timeSlot[0]->status == 'available')
                    <x-warning-button class="ms-3" wire:click="confirmBlockSlot({{ $timeSlot[0]->id }})" wire:loading.attr="disabled">
                        <svg class="w-4 h-4 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                          </svg>
                    </x-warning-button>
                    @endif
                    @if($timeSlot[0]->status == 'blocked')
                    <x-danger-button class="ms-3" wire:click="confirmUnblockSlot({{ $timeSlot[0]->id }})" wire:loading.attr="disabled">
                        <svg class="w-4 h-4 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9h13a5 5 0 0 1 0 10H7M3 9l4-4M3 9l4 4"/>
                        </svg>
                    </x-danger-button>
                    @endif
                    @if($timeSlot[0]->status !== 'booked')
                    <x-danger-button class="ms-3" wire:click="confirmDeleteSlot({{ $timeSlot[0]->id }})" wire:loading.attr="disabled">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </x-danger-button>
                    @endif
                </div>
                @if($timeSlot[0]->status == 'booked')
                <p class="text-xs">{{(__('slots.statuses.booked'))}}</p>
                @endif
                @if($timeSlot[0]->status == 'blocked')
                <p class="text-xs">{{(__('slots.statuses.blocked'))}}</p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @empty
        <div class="text-center text-gray-500 dark:text-gray-400">{{ __('slots.no_slots') }}</div>
    @endforelse
</div>
