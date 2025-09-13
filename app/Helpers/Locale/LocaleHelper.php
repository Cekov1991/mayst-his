<?php

namespace App\Helpers\Locale;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LocaleHelper
{
    /**
     * Available locales with their display names
     */
    public const LOCALES = [
        'en' => [
            'code' => 'en',
            'name' => 'English',
            'native' => 'English',
            'flag' => '🇺🇸',
        ],
        'mk' => [
            'code' => 'mk',
            'name' => 'Macedonian',
            'native' => 'Македонски',
            'flag' => '🇲🇰',
        ],
    ];

    /**
     * Get all available locales
     */
    public static function getAvailableLocales(): array
    {
        return self::LOCALES;
    }

    /**
     * Get current locale
     */
    public static function getCurrentLocale(): string
    {
        return App::getLocale();
    }

    /**
     * Check if locale is available
     */
    public static function isValidLocale(string $locale): bool
    {
        return array_key_exists($locale, self::LOCALES);
    }

    /**
     * Set application locale
     */
    public static function setLocale(string $locale): bool
    {
        if (!self::isValidLocale($locale)) {
            return false;
        }

        App::setLocale($locale);
        Session::put('locale', $locale);

        return true;
    }

    /**
     * Get locale from session or default
     */
    public static function getSessionLocale(): string
    {
        $sessionLocale = Session::get('locale');

        return self::isValidLocale($sessionLocale) ? $sessionLocale : config('app.locale');
    }

    /**
     * Initialize locale from session
     */
    public static function initializeLocale(): void
    {
        $locale = self::getSessionLocale();
        App::setLocale($locale);
    }

    /**
     * Get locale display information
     */
    public static function getLocaleInfo(string $locale = null): array
    {
        $locale = $locale ?? self::getCurrentLocale();

        return self::LOCALES[$locale] ?? self::LOCALES[config('app.locale')];
    }

    /**
     * Get route with locale parameter (for locale switching links)
     */
    public static function getLocalizedRoute(string $locale): string
    {
        return route('locale.switch', ['locale' => $locale]);
    }

    /**
     * Get HIS translation key with fallback
     */
    public static function trans(string $key, array $replace = []): string
    {
        $fullKey = "his.{$key}";

        if (trans($fullKey) !== $fullKey) {
            return trans($fullKey, $replace);
        }

        // Fallback to English if current locale doesn't have the key
        App::setLocale('en');
        $translation = trans($fullKey, $replace);
        App::setLocale(self::getCurrentLocale());

        return $translation;
    }

    /**
     * Get text direction for current locale (RTL support if needed later)
     */
    public static function getDirection(): string
    {
        return 'ltr'; // All current locales are LTR
    }
}
