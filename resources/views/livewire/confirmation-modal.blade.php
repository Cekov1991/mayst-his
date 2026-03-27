<div>
    <x-confirmation-modal wire:model.live="show">
        <x-slot name="title">
            {{ $title }}
        </x-slot>

        <x-slot name="content">
            {{ $content }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="close" wire:loading.attr="disabled">
                {{ $cancelText }}
            </x-secondary-button>
            <x-danger-button class="ms-3" wire:click="confirm" wire:loading.attr="disabled">
                {{ $confirmText }}
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>
</div>
