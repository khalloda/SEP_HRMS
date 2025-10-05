# Salary Structure Edit Resets Components Bug

## Summary
- Issue: Opening the salary structure edit page caused every component dropdown to reset to `BASIC_SALARY`, even while the database retained the correct `component_id` values.
- Business impact: HR users risked overwriting the structure if they saved without re-selecting each component.
- Root cause: Alpine's repeater initialisation generated new UUIDs and blank rows before the seeded data was normalised; combined with row-level `x-model` bindings that referenced `rows[index].component`, the select elements always defaulted to the first option.

## Investigation
- **Database sanity** – Confirmed persisted components via `php artisan tinker` and direct MySQL queries.
- **Controller flow** – `SalaryStructureController@edit` simply passed hydrated `structureComponents`; no server-side transformations.
- **Frontend tracing** – Browser console inspection (`Alpine.$data(...)`) showed the seeded rows held the correct component IDs, but the `<select>` values remained `43` for every row. Because the Blade markup bound `x-model="rows[index].component"`, Alpine re-used the proxy for index `0` across rows.
- **Assets cache** – Verified the Vite bundle (`public/build/assets/app-*.js`) still had the old normalisation logic; rebuilding surfaced the mismatch between model values and `<option>` values.

## Fix (Current Commit)
- Updated `resources/views/salary-structures/create.blade.php` and `edit.blade.php`:
  - Normalised row bindings to use `x-model="row.component"` / `row.amount` etc. while keeping unique field names based on `row.uuid`.
  - Added `:selected="component.id === row.component"` to keep `<option>` lists and model values in sync during first render.
  - Ensured seeded UUIDs are preserved (`component.row_key` fallback) so Alpine doesn't regenerate ids on load.
- Refined `resources/js/app.js` repeater helper:
  - Normalise seeded component payloads and cast IDs to strings.
  - Added debug logging (temporary) to verify row state.
- Rebuilt frontend assets via `npm run build` and cleared compiled views with `php artisan view:clear`.
- Verified the page retains component selections after refresh and during edit/save flows.

## Tests & Verification
- Manual smoke test on `hrms.local` confirming edit form retains component selections and persists unchanged when saved.
- Automated suite partially run (`php artisan test`) – feature tests currently fail due to legacy database schema lacking `email_verified_at` and foreign key truncation (documented in runbook). No new failures introduced by the fix.

## Rollback Plan
1. Revert the Blade and JS changes (`git revert <commit_sha>`).
2. Run `npm run build` and `php artisan view:clear` to restore previous assets and views.
3. If incorrect structures were saved post-fix, restore affected `salary_structure_components` records from backup.

## Status
✅ Fix applied; frontend validated. Automated payroll feature tests remain non-green in this environment because of pre-existing schema constraints.

