<?php

namespace Tests\Unit\Payroll;

use App\Models\PayslipLine;
use Tests\TestCase;

class PayslipLineModelTest extends TestCase
{
    public function test_display_name_falls_back_to_component_name(): void
    {
        app()->setLocale('en');

        $line = new PayslipLine([
            'component_name' => 'Basic Salary',
        ]);

        $this->assertSame('Basic Salary', $line->component_display_name);
    }

    public function test_taxable_accessor_prefers_new_column_and_falls_back(): void
    {
        $line = new PayslipLine(['taxable' => true, 'is_taxable' => false]);
        $this->assertTrue($line->taxable);

        $fallback = new PayslipLine(['is_taxable' => true]);
        $this->assertTrue($fallback->taxable);
    }

    public function test_priority_order_synchronises_with_legacy_priority(): void
    {
        $line = new PayslipLine();
        $line->priority_order = 5;

        $this->assertSame(5, $line->priority_order);
        $this->assertSame(5, $line->priority);
    }
}
