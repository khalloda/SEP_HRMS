# Task: Phase D1 Step 20 - Payroll Update Currency Test

## Problem
We lacked regression coverage that the update endpoint accepts only configured currencies and rejects invalid ones after centralizing payroll currency logic.

## Approach
- Added `tests/Feature/Payroll/PayrollUpdateTest.php` with happy-path and invalid currency scenarios using the helper-driven configuration.

## Files Changed
- `tests/Feature/Payroll/PayrollUpdateTest.php`

## Tests
- `php artisan test --filter=PayrollUpdateTest`

## Rollback Plan
1. Remove the new feature test file.
