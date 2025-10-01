<?php

namespace Tests\Unit\Config;

use Tests\TestCase;

class PayrollConfigTest extends TestCase
{
    public function test_payroll_enabled_helper_defaults_to_false(): void
    {
        config()->set('payroll.enabled', null);

        $this->assertFalse(payrollEnabled());
    }

    public function test_payroll_enabled_helper_reflects_config_override(): void
    {
        config()->set('payroll.enabled', true);

        $this->assertTrue(payrollEnabled());
        $this->assertTrue(app('payroll.enabled'));
    }

    public function test_config_file_exposes_expected_keys(): void
    {
        $config = config('payroll');

        $this->assertIsArray($config);
        $this->assertArrayHasKey('enabled', $config);
        $this->assertArrayHasKey('expression_engine', $config);
        $this->assertArrayHasKey('queue_enabled', $config);
    }
}
