<?php

namespace App\Http\Middleware;

use App\Helpers\Locale\LocaleHelper;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Initialize locale from session or use default
        LocaleHelper::initializeLocale();

        return $next($request);
    }
}
