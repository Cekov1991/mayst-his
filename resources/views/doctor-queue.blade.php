<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ his_trans('visits.today_queue') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="px-4 sm:px-6 lg:px-8">
                <!-- Header Section -->
                <div class="sm:flex sm:items-center mb-6">
                    <div class="sm:flex-auto">
                        <h1 class="text-base font-semibold text-gray-900 dark:text-white">{{ his_trans('visits.today_queue') }}</h1>
                        <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">Manage your daily patient queue and visit statuses in real-time.</p>
                    </div>
                </div>

                <!-- Livewire Doctor Queue Component -->
                @livewire('doctor-queue')
            </div>
        </div>
    </div>
</x-app-layout>
