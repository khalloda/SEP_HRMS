# Task: Phase D1 Step 25 - Salary Structure UX Enhancements

## Problem
After introducing the salary structure views, we still need richer UX: component validation feedback, dynamic row ordering, and inline helper text tied to calculation behaviour.

## Approach (planned)
- Add client-side validation and reorder controls for component rows.
- Surface calculation hints (e.g., dependency warnings, formula tips) within the create/edit forms.
- Ensure errors return users to the form with their component state intact.

## Files Expected
- `resources/views/salary-structures/create.blade.php`
- `resources/views/salary-structures/edit.blade.php`
- `resources/js` helpers as needed
- Associated feature tests

## Tests
- Pending definition (likely Playwright + feature coverage).

## Rollback Plan
- Remove UX scripts and revert Blade adjustments if needed.
