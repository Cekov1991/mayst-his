<div>
    <div class="mb-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
            {{ $prescription ? __('common.edit') . ' ' . __('prescriptions.title') : __('prescriptions.add_prescription') }}
        </h3>
    </div>

    <form wire:submit="save" class="space-y-6">
        <!-- Prescription Notes -->
        <div>
            <label for="notes" class="block text-sm font-medium text-gray-900 dark:text-white">{{ __('prescriptions.notes') }}</label>
            <textarea wire:model="notes" id="notes" rows="2"
                      class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                      placeholder="General prescription notes..."></textarea>
            @error('notes')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Medication Items -->
        <div>
            <div class="flex justify-between items-center mb-4">
                <label class="block text-sm font-medium text-gray-900 dark:text-white">{{ __('prescriptions.items') }}</label>
                <button type="button" wire:click="addMedicationRow"
                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-900/20 dark:text-indigo-300 dark:hover:bg-indigo-900/40"
                        wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="addMedicationRow">+ {{ __('prescriptions.add_item') }}</span>
                    <span wire:loading wire:target="addMedicationRow">{{ __('common.loading') }}...</span>
                </button>
            </div>

            <div class="space-y-4">
                @foreach($items as $index => $item)
                    <div class="medication-item p-4 bg-gray-50 dark:bg-gray-900/20 rounded-lg" wire:key="item-{{ $index }}">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('prescriptions.drug_name') }}</label>
                                <input type="text" wire:model="items.{{ $index }}.drug_name"
                                       class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                       required>
                                @error("items.{$index}.drug_name")
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('prescriptions.form') }}</label>
                                <select wire:model="items.{{ $index }}.form"
                                        class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:focus:ring-indigo-500"
                                        required>
                                    <option value="drops">{{ __('prescriptions.drug_forms.drops') }}</option>
                                    <option value="ointment">{{ __('prescriptions.drug_forms.ointment') }}</option>
                                    <option value="tablet">{{ __('prescriptions.drug_forms.tablet') }}</option>
                                    <option value="capsule">{{ __('prescriptions.drug_forms.capsule') }}</option>
                                    <option value="other">{{ __('prescriptions.drug_forms.other') }}</option>
                                </select>
                                @error("items.{$index}.form")
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('prescriptions.strength') }}</label>
                                <input type="text" wire:model="items.{{ $index }}.strength"
                                       class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                       placeholder="e.g. 0.5mg">
                                @error("items.{$index}.strength")
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('prescriptions.dosage_instructions') }}</label>
                                <input type="text" wire:model="items.{{ $index }}.dosage_instructions"
                                       class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"
                                       placeholder="e.g. 1 drop twice daily"
                                       required>
                                @error("items.{$index}.dosage_instructions")
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex justify-between items-end">
                                <div class="flex space-x-2">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('prescriptions.duration_days') }}</label>
                                        <input type="number" wire:model="items.{{ $index }}.duration_days" min="1"
                                               class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500">
                                        @error("items.{$index}.duration_days")
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('prescriptions.repeats') }}</label>
                                        <input type="number" wire:model="items.{{ $index }}.repeats" min="0"
                                               class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500">
                                        @error("items.{$index}.repeats")
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <button type="button" wire:click="removeMedicationRow({{ $index }})"
                                        wire:loading.attr="disabled"
                                        @if(!$this->canRemoveItem) disabled @endif
                                        class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @error('items')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Button -->
        <div class="flex justify-between pt-6">
            <a href="{{ route('visits.prescriptions', $visit) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600">
                ← {{ __('common.back') }}
            </a>

            <button type="submit" wire:loading.attr="disabled"
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-600 disabled:opacity-50">
                <span wire:loading.remove wire:target="save">{{ $prescription->exists ? __('common.update') : __('common.save') }}</span>
                <span wire:loading wire:target="save">{{ __('common.processing') }}...</span>
            </button>
        </div>
    </form>
</div>
