<?php

namespace App\Livewire;

use Livewire\Component;

class ConfirmationModal extends Component
{
    public bool $show = false;

    public string $title = '';

    public string $content = '';

    public string $confirmText = '';

    public string $cancelText = '';

    public string $confirmEvent = '';

    public array $confirmParams = [];

    protected $listeners = ['openConfirmationModal' => 'open'];

    public function open(string $title, string $content, string $confirmEvent, array $confirmParams = [], ?string $confirmText = null, ?string $cancelText = null): void
    {
        $this->title = $title;
        $this->content = $content;
        $this->confirmEvent = $confirmEvent;
        $this->confirmParams = $confirmParams;
        $this->confirmText = $confirmText ?? __('common.yes');
        $this->cancelText = $cancelText ?? __('common.cancel');
        $this->show = true;
    }

    public function close(): void
    {
        $this->show = false;
        $this->reset(['title', 'content', 'confirmEvent', 'confirmParams', 'confirmText', 'cancelText']);
    }

    public function confirm(): void
    {
        if ($this->confirmEvent) {
            $this->dispatch($this->confirmEvent, ...$this->confirmParams);
        }
        $this->close();
    }

    public function render()
    {
        return view('livewire.confirmation-modal');
    }
}
