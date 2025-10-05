# Task: Repair reports config for payroll definitions

## Problem
`config/reports.php` contained malformed array syntax (escaped newlines, double-escaped namespaces, misaligned brackets) causing PHP parse errors that blocked any artisan command, including tests.

## Approach
- Rewrote the configuration file to cleanly define each report adapter entry.
- Ensured the payroll summary definition sits within the `definitions` array and uses proper namespaces.
- Preserved existing adapter metadata while normalizing formatting.

## Files Changed
- `config/reports.php`

## Tests
- `php artisan test --testsuite=Unit`

## Rollback Plan
1. `git revert <commit-hash>`.
2. Alternatively, restore the previous version of `config/reports.php` from backup.
3. Run `php artisan config:clear` to flush cached configuration.
