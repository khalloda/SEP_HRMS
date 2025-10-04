# Salary Structure Edit Resets Components Bug

## Summary
When opening the salary structure edit screen, every component dropdown rendered as `BASIC_SALARY` even though the database retained the original `component_id` values. Amounts and formulas stayed correct, but saving without reselecting each component risked overwriting associations.

## Investigation
- **Database sanity** – Tinker query (`php artisan tinker --execute '...->structureComponents->map(...)'`) confirmed persisted `component_id` values (e.g., `43`, `49`) match expectations.
- **Controller flow** – `SalaryStructureController@edit` simply loads `$salaryStructure` with `structureComponents.component`; no server-side defaulting.
- **Blade/Alpine initialisation** – Edit view seeded Alpine state via `componentRepeater(@js(...), @js(old(... mapWithKeys ...)))`. Inside `componentRepeater` the helper `ensureRow()` ran before assigning seeded rows, pushing a blank `{ component: '' }` row. That empty entry occupied index 0, so the `<select>` defaulted to its first option (`BASIC_SALARY`) even though subsequent entries contained the real component IDs.

## Fix
- Updated `componentRepeater` in both `create.blade.php` and `edit.blade.php`:
  - Introduced a shared `newRow()` helper.
  - Seed data now populates `rows` before `ensureRow()` runs; the helper only injects a blank row when no components exist.
  - Removed redundant in-line blank row creation logic.
- Added PHPUnit feature test `tests/Feature/SalaryStructure/SalaryStructureEditTest.php` to assert that seeded `component_id` payloads are present in the rendered edit form.
- Added Playwright E2E coverage (`salary-structures.spec.ts`) to verify the UI retains distinct component selections after opening and saving the edit form.

## Rollback Plan
1. `git revert <fix-commit-hash>` to restore previous view scripts and tests.
2. Clear caches/assets (`php artisan optimize:clear`) to ensure old Alpine bundles reload.
3. If end-users saved incorrect associations prior to rollback, restore `salary_structure_components` rows from the latest DB backup.

## Status
Fix implemented, tests passing.

