<?php

namespace {
    if (! function_exists('payrollEnabled')) {
        /**
         * Determine if the enhanced payroll module is enabled.
         */
        function payrollEnabled(): bool
        {
            return (bool) config('payroll.enabled', false);
        }
    }
}

namespace App\Providers {

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Schema::defaultStringLength(191); // ensure compatibility when services register early

        $this->app->singleton('payroll.enabled', static fn (): bool => payrollEnabled());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191); // ensure compatibility with legacy MySQL defaults
    }
}

}
