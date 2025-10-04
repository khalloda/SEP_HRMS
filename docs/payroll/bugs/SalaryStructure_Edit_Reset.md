# Salary Structure Edit Resets Components Bug

## Summary
When opening the salary structure edit screen, every component dropdown rendered as `BASIC_SALARY` even though the database retained the original `component_id` values. Amounts and formulas stayed correct, but saving without reselecting each component risked overwriting associations.

## Investigation
- **Database sanity** – `php artisan tinker --execute '$s=App\Models\SalaryStructure::with("structureComponents")->whereHas("structureComponents")->latest()->first(); dump($s?->structureComponents->map(fn ($c)=>[$c->component_id,$c->formula_expr,$c->value_numeric]));'` confirmed persisted component IDs align with expectations.
- **Controller flow** – `SalaryStructureController@edit` simply loads structure components and passes them to the view; no server-side mutation.
- **Blade/Alpine initialisation** – Edit view seeded Alpine with `componentRepeater(@js($components), @js(...mapWithKeys(...)))`. The repeater helper normalised seed data by calling `Object.values` and then immediately appended a placeholder `{ component: '' }` when the initial collection length was `0`. Because the helper executed before the seeded rows were appended, the blank row occupied index `0`, so each `<select>` defaulted to the first option (`BASIC_SALARY`).

## Fix (Commit `0df380d`)
- Refactored `componentRepeater` in both `create.blade.php` and `edit.blade.php`:
  - Added explicit `normaliseSeed` helper that converts either object maps (from `mapWithKeys`) or arrays into a clean array of component objects **before** seeding.
  - Introduced a shared `newRow()` factory; blank rows are now only added when the final seed array is empty (create flow).
  - Ensured all IDs are stringified (`String(item.id)` / `String(component.component_id)`) to keep `x-model` and `<option :value>` comparisons consistent.
  - Preserved row priorities via a central `reorder` helper.
- Updated Alpine add/remove handlers to use `newRow()` consistently and avoid reintroducing blanks when rows already exist.
- Added PHP feature test `tests/Feature/SalaryStructure/SalaryStructureEditTest.php` to verify the view data contains the correct seeded component payload.
- Added Playwright regression (existing `salary-structures.spec.ts` extended) to confirm edit page retains selections and saving without changes keeps component IDs intact. Playwright config now defaults to `http://hrms.local` and respects env overrides (`PLAYWRIGHT_BASE_URL`, `PLAYWRIGHT_USER`, `PLAYWRIGHT_PASSWORD`).

## Rollback Plan
1. `git revert 0df380d` to restore previous Blade/Alpine logic and tests.
2. Clear caches/assets (`php artisan optimize:clear`) to flush compiled view/JS state.
3. If incorrect associations were saved post-fix, restore affected `salary_structure_components` rows from the latest database backup.

## Status
Fix deployed and tests passing.

