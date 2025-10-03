# Payroll Formula Conditional Logic Runbook

## Summary
Enable conditional expressions inside salary component formulas using the upgraded parser running in "safe" engine mode, controlled by a feature flag. Goal: support Excel-like `IF` branching without regressing existing payroll runs.

## Prerequisites
- All automated tests green (`php artisan test`).
- Database backups taken (salary structures + payroll runs).
- Ensure `config/payroll.php` flag `use_safe_engine_conditionals` present (defaults false).
- Communicate change window to HR & Accounting stakeholders.

## Deployment Steps
1. **Code Deploy**: Push branch to staging, run migrations (none expected) and config cache clear.
2. **Feature Flag Check**: Confirm `.env` contains `PAYROLL_SAFE_ENGINE_CONDITIONALS=false` post-deploy; leave disabled until smoke tests complete.
3. **Staging Validation**:
   - Create test salary structure with conditional formula, e.g., `IF(BASIC_SALARY>10000, BASIC_SALARY*0.1, BASIC_SALARY*0.05)`.
   - Run payroll calculation for sample employee; inspect payslip lines for correct deduction.
   - Trigger negative path (condition false) to verify branching.
4. **Enable Flag**: Toggle `.env` to `PAYROLL_SAFE_ENGINE_CONDITIONALS=true`; run `php artisan config:clear`.
5. **Regression Sweep**:
   - Re-run payroll for existing structures without conditionals to confirm parity with previous outputs (compare totals against baseline export).
   - Generate payslip PDFs to ensure numbers render correctly in EN/AR.
   - Validate log files for parser errors; ensure no new warnings.
6. **Production Rollout**: Mirror staging steps, ensuring backup before flag toggle. Monitor queue/cron jobs for payroll runs.

## Monitoring & Verification
- Laravel logs (`storage/logs/laravel.log`) for `Payroll component calculation failed` entries.
- Activity log for payroll runs to confirm status transitions remain accurate.
- Optional comparison script to diff payslip totals pre/post feature flag for a sample month.
- Frontend smoke test: confirm salary structure edit form still saves formulas; check RTL layout unaffected.

## Rollback Plan
- Set `PAYROLL_SAFE_ENGINE_CONDITIONALS=false`; run `php artisan config:clear`.
- If issues persist, deploy previous commit hash (documented in change record).
- Recalculate impacted payroll runs using legacy formulas; regenerate payslip PDFs if numbers changed.
- Notify stakeholders of rollback and review error logs to identify formula inputs causing failure.
