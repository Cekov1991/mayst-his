<?php

namespace App\Helpers;

use Spatie\Browsershot\Browsershot;

class PdfHelper
{
    /**
     * Configure Browsershot for headless Docker environment.
     */
    public static function configureBrowsershot(Browsershot $browsershot): Browsershot
    {
        // Chrome args for headless Docker environment
        // Disable crash reporting to avoid crashpad handler issues
        return $browsershot->setOption('args', [
            '--no-sandbox',
            '--disable-setuid-sandbox',
            '--disable-dev-shm-usage',
            '--disable-gpu',
            '--disable-crash-reporter',
            '--disable-breakpad',
            '--crash-dumps-dir=/tmp',
            '--disable-extensions',
            '--disable-background-networking',
            '--disable-background-timer-throttling',
            '--disable-backgrounding-occluded-windows',
            '--disable-component-extensions-with-background-pages',
            '--disable-features=TranslateUI',
            '--disable-ipc-flooding-protection',
            '--disable-renderer-backgrounding',
            '--enable-features=NetworkService,NetworkServiceInProcess',
            '--force-color-profile=srgb',
            '--hide-scrollbars',
            '--metrics-recording-only',
            '--mute-audio',
            '--no-first-run',
            '--safebrowsing-disable-auto-update',
            '--disable-software-rasterizer',
        ]);
    }
}

