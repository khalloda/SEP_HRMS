# Task: Phase D1 Step 21 - Payroll Policy Update Tests

## Problem
After adding helper-driven currency flows, we still lacked regression coverage for who can update a payroll run, especially across draft and locked states.

## Approach
- Added `tests/Feature/Payroll/PayrollPolicyTest.php` to assert update permissions for HR Admin, Accounting Manager, HR Coordinator, and locked run scenarios.

## Files Changed
- `tests/Feature/Payroll/PayrollPolicyTest.php`

## Tests
- `php artisan test --filter=PayrollPolicyTest`

## Rollback Plan
1. Remove the new policy test file or revert the added cases.
