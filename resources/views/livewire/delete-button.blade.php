<div>
    <button wire:click="confirmDeletion" class="{{ $buttonClass }}" wire:loading.attr="disabled">
        <span wire:loading.remove wire:target="confirmDeletion">{{ $buttonText }}</span>
        <span wire:loading wire:target="confirmDeletion">{{ __('common.loading') }}</span>
    </button>

    <x-confirmation-modal wire:model.live="confirmingDeletion">
        <x-slot name="title">
            {{ $this->getTitle() }}
        </x-slot>

        <x-slot name="content">
            {{ $this->getContent() }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('confirmingDeletion', false)" wire:loading.attr="disabled">
                {{ __('common.cancel') }}
            </x-secondary-button>
            <x-danger-button class="ms-3" wire:click="delete" wire:loading.attr="disabled">
                {{ __('common.delete') }}
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>
</div>

