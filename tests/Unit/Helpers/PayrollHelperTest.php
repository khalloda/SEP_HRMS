<?php

namespace Tests\Unit\Helpers;

use Illuminate\Support\Arr;
use Tests\TestCase;

class PayrollHelperTest extends TestCase
{
    public function test_payroll_currencies_uses_configured_values(): void
    {
        config()->set('payroll.currencies', ['EGP' => 'EGP', 'USD' => 'USD']);

        $currencies = payrollCurrencies();

        $this->assertSame(['EGP' => 'EGP', 'USD' => 'USD'], $currencies);
    }

    public function test_payroll_currencies_falls_back_to_egp_when_config_empty(): void
    {
        config()->set('payroll.currencies', []);

        $currencies = payrollCurrencies();

        $this->assertSame(['EGP' => 'EGP'], $currencies);
    }
}
