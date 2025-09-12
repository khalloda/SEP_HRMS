<?php

namespace App\Http\Middleware;

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
        // Check if locale is set in session or request
        $locale = $request->session()->get('locale', config('app.locale'));
        
        // Support locale switching via URL parameter
        if ($request->has('locale')) {
            $requestedLocale = $request->get('locale');
            if (in_array($requestedLocale, ['en', 'ar'])) {
                $locale = $requestedLocale;
                $request->session()->put('locale', $locale);
            }
        }
        
        // Set the application locale
        app()->setLocale($locale);
        
        return $next($request);
    }
}
