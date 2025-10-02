# Task: Step 07 Phase D1 - Payroll Run Show View

## Problem
The payroll run show endpoint rendered an oversized monolithic Blade template, lacked reusable partials for status banners and calculation summaries, and relied on the view to pull flash/session data directly. Route model binding also returned empty models because the resource parameter name did not match the controller signature, causing action URLs to break in practice and in tests.

## Approach
- Introduced `payroll.partials.status-banners` and `payroll.partials.calculation-summary` to centralise flash messaging, correlation details, and calculation breakdowns for reuse across upcoming views.
- Rebuilt `resources/views/payroll/show.blade.php` to consume the new partials, streamline the layout, and gate lifecycle forms via policies while keeping validation warnings and calculation placeholders distinct.
- Passed view state from `PayrollController@show` (merged validation issues, correlation reference, calculation payloads, flash messages) so UI no longer queries the session directly.
- Mapped the resource parameter to `payrollRun` and updated feature tests with Mockery-backed calculation service expectations plus new coverage for session validation issues to keep the page deterministic.

## Files Changed
- `resources/views/payroll/partials/status-banners.blade.php`
- `resources/views/payroll/partials/calculation-summary.blade.php`
- `resources/views/payroll/show.blade.php`
- `app/Http/Controllers/PayrollController.php`
- `routes/web.php`
- `tests/Feature/Payroll/PayrollShowViewTest.php`

## Tests
- `php artisan test --filter=PayrollShowViewTest`

## Rollback Plan
1. Remove the new partials and restore `resources/views/payroll/show.blade.php` to the previous single-file implementation.
2. Revert `PayrollController@show`, `routes/web.php`, and `PayrollShowViewTest.php` to their prior versions.
3. Clear cached routes/config if necessary: `php artisan route:clear && php artisan config:clear`.
