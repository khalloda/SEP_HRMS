# Task: Payroll Create View Scaffold

## Problem
The payroll creation route existed but returned a 404 because no Blade template was present. HR managers could not launch new payroll runs even though the controller already supplied suggested period dates and validation rules.

## Approach
- Added esources/views/payroll/create.blade.php with a Bootstrap form aligned to the controller validation (period dates, currency, notes, approval toggle).
- Surfaced flash/validation messages via the shared status banner partial and inline error feedback to keep UX consistent with index/show.
- Preloaded suggested period/pay dates and default currency selections so the form is ready-to-submit out of the box.

## Files Changed
- esources/views/payroll/create.blade.php

## Tests
- php artisan test --filter=PayrollIndexViewTest

## Rollback Plan
1. Delete esources/views/payroll/create.blade.php.
2. Clear compiled views: php artisan view:clear.
