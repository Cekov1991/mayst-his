<?php

namespace App\View\Components;

use App\Helpers\Locale\LocaleHelper;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class LocaleSwitcher extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.locale-switcher', [
            'currentLocale' => LocaleHelper::getCurrentLocale(),
            'availableLocales' => LocaleHelper::getAvailableLocales(),
            'currentLocaleInfo' => LocaleHelper::getLocaleInfo(),
        ]);
    }
}
