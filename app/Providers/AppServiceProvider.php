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

    if (! function_exists('payrollCurrencies')) {
        /**
         * Retrieve configured payroll currencies keyed by display label.
         * Falls back to EGP when configuration is missing.
         */
        function payrollCurrencies(): array
        {
            $currencies = config('payroll.currencies', []);

            if (empty($currencies)) {
                return ['EGP' => 'EGP'];
            }

            return $currencies;
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
        $this->app->singleton('payroll.currencies', static fn (): array => payrollCurrencies());
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
