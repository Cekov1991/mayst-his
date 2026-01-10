<?php

namespace App\Livewire;

use Livewire\Component;

class DeleteButton extends Component
{
    public $model;

    public string $routeName;

    public array $routeParams = [];

    public ?string $redirectRouteName = null;

    public ?string $title = null;

    public ?string $content = null;

    public string $buttonText;

    public string $buttonClass = 'text-sm font-medium text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300';

    public bool $confirmingDeletion = false;

    public function mount($model, string $routeName, array $routeParams = [], ?string $redirectRouteName = null, ?string $title = null, ?string $content = null, ?string $buttonText = null): void
    {
        $this->model = $model;
        $this->routeName = $routeName;
        $this->routeParams = $routeParams;
        $this->redirectRouteName = $redirectRouteName;
        $this->title = $title;
        $this->content = $content;
        $this->buttonText = $buttonText ?? __('common.delete');
    }

    public function confirmDeletion(): void
    {
        $this->confirmingDeletion = true;
    }

    public function delete()
    {
        // Authorize using visit if available (workspace routes use this pattern)
        if (isset($this->routeParams['visit'])) {
            $this->authorize('accessMedicalWorkspace', $this->routeParams['visit']);
        } else {
            // Fallback: try to authorize using the model's policy
            try {
                $this->authorize('delete', $this->model);
            } catch (\Exception $e) {
                // If authorization fails, let it throw - Laravel will handle it
                throw $e;
            }
        }


        // Delete the model
        $this->model->delete();

        // Build redirect route - use custom redirect route if provided, otherwise remove .destroy to get list view route
        $redirectRoute = $this->redirectRouteName ?? str_replace('.destroy', '', $this->routeName);
        // Set flash message
        session()->flash('success', __('common.messages.deleted_successfully'));

        // Redirect to list view with route params (e.g., visits.treatments with $visit)
        return $this->redirect(route($redirectRoute, $this->routeParams), navigate: true);
    }

    public function getTitle(): string
    {
        return $this->title ?? __('common.delete_confirm_title');
    }

    public function getContent(): string
    {
        return $this->content ?? __('common.delete_confirm_message');
    }

    public function render()
    {
        return view('livewire.delete-button');
    }
}
