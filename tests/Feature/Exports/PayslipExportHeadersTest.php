<?php

namespace Tests\Feature\Exports;

use Tests\TestCase;

class PayslipExportHeadersTest extends TestCase
{
    public function test_payslip_export_headings_are_translated_in_arabic(): void
    {
        app()->setLocale('ar');

        $headings = [
            __('payroll.exports.employee_code'),
            __('payroll.exports.employee_name'),
            __('payroll.exports.department'),
            __('payroll.exports.position'),
            __('payroll.exports.pay_period_start'),
            __('payroll.exports.pay_period_end'),
            __('payroll.exports.pay_date'),
            __('payroll.exports.gross_pay'),
            __('payroll.exports.total_deductions'),
            __('payroll.exports.net_pay'),
            __('payroll.exports.currency'),
            __('payroll.exports.status'),
        ];

        $this->assertContains('كود الموظف', $headings);
        $this->assertContains('اسم الموظف', $headings);
        $this->assertContains('القسم', $headings);
        $this->assertContains('المنصب', $headings);
        $this->assertContains('بداية فترة الدفع', $headings);
        $this->assertContains('نهاية فترة الدفع', $headings);
        $this->assertContains('تاريخ الدفع', $headings);
        $this->assertContains('إجمالي الراتب', $headings);
        $this->assertContains('إجمالي الاستقطاعات', $headings);
        $this->assertContains('صافي الراتب', $headings);
        $this->assertContains('العملة', $headings);
        $this->assertContains('الحالة', $headings);
    }
}
