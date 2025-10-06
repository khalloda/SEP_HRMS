<?php

namespace Tests\Unit\Payroll;

use App\Support\Payroll\ComponentName;
use Tests\TestCase;

class ComponentNameTest extends TestCase
{
    /** @test */
    public function returns_preferred_locale_name()
    {
        app()->setLocale('en');
        $comp = ['name_en' => 'Housing Allowance', 'name_ar' => 'بدل سكن', 'code' => 'HOUSING_ALLOWANCE'];
        $this->assertSame('Housing Allowance', ComponentName::display($comp));

        app()->setLocale('ar');
        $this->assertSame('بدل سكن', ComponentName::display($comp));
    }

    /** @test */
    public function falls_back_to_other_locale_then_code_then_unknown()
    {
        app()->setLocale('en');
        $comp = ['name_en' => '', 'name_ar' => 'بدل', 'code' => 'X'];
        $this->assertSame('بدل', ComponentName::display($comp));

        $comp = ['name_en' => '', 'name_ar' => '', 'code' => 'X'];
        $this->assertSame('X', ComponentName::display($comp));

        $comp = ['name_en' => '', 'name_ar' => '', 'code' => ''];
        $this->assertNotSame('', ComponentName::display($comp));
    }
}


