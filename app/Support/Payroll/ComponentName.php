<?php

namespace App\Support\Payroll;

final class ComponentName
{
    public static function display($component, ?string $locale = null): string
    {
        $locale = $locale ?: (function_exists('app') ? app()->getLocale() : 'en');
        $preferred = $locale === 'ar' ? 'name_ar' : 'name_en';
        $fallback = $locale === 'ar' ? 'name_en' : 'name_ar';

        $value = trim((string) data_get($component, $preferred, ''));
        if ($value === '') {
            $value = trim((string) data_get($component, $fallback, ''));
        }
        if ($value === '') {
            $value = (string) data_get($component, 'code', __('Unknown Component'));
        }

        return $value;
    }
}


