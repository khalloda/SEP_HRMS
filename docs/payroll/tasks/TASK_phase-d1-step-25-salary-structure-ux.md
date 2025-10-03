# Task: Phase D1 Step 25 - Salary Structure UX Enhancements

## Problem
After introducing the salary structure views, we still needed richer UX: component validation feedback, dynamic row ordering, and inline helper text tied to calculation behaviour.

## Approach
- Added drag-and-drop ordering for component rows using SortableJS with priority normalization.
- Ensured currency dropdowns pull from `payrollCurrencies()` with sensible fallbacks.
- Surfaced inline validation messaging and ensured at least one component row persists.

## Files Changed
- `package.json`
- `package-lock.json`
- `resources/views/salary-structures/create.blade.php`
- `resources/views/salary-structures/edit.blade.php`
- `docs/payroll/CHANGELOG.md`

## Tests
- `php artisan test --filter=SalaryStructureViewTest`

## Rollback Plan
1. Remove SortableJS dependency (`npm uninstall sortablejs`).
2. Restore salary structure views to static forms.
3. Update changelog entry accordingly.
