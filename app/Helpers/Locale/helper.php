<?php

use App\Helpers\Locale\LocaleHelper;

if (!function_exists('his_trans')) {
    /**
     * Translate a HIS key with fallback support
     */
    function his_trans(string $key, array $replace = []): string
    {
        return LocaleHelper::trans($key, $replace);
    }
}

if (!function_exists('his_locale')) {
    /**
     * Get current HIS locale
     */
    function his_locale(): string
    {
        return LocaleHelper::getCurrentLocale();
    }
}

if (!function_exists('his_locales')) {
    /**
     * Get all available HIS locales
     */
    function his_locales(): array
    {
        return LocaleHelper::getAvailableLocales();
    }
}

if (!function_exists('his_locale_info')) {
    /**
     * Get locale information (name, flag, etc.)
     */
    function his_locale_info(string $locale = null): array
    {
        return LocaleHelper::getLocaleInfo($locale);
    }
}

if (!function_exists('his_switch_url')) {
    /**
     * Get locale switching URL
     */
    function his_switch_url(string $locale): string
    {
        return route('locale.switch', ['locale' => $locale]);
    }
}
