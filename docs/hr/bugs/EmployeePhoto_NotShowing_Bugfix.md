# Employee Photo Not Showing — Bugfix

## Summary
- Implemented signed URL delivery for employee photos stored on the private disk, ensuring only users with `employees.view` permission can access images.
- Updated controller logic to validate signatures and hashed metadata while maintaining private storage security.
- Added Laravel feature coverage and Playwright checks to confirm photos appear on list/profile pages and fall back to default avatars when required.

## Implementation Details
- Added signed URL generation with per-viewer caching in `App\Models\Employee`.
- Updated `EmployeeController@servePhoto` to rely on signed URLs with hash verification.
- Introduced configuration `EMPLOYEE_PHOTO_SIGNED_URL_TTL` (default 900 seconds) under `config/services.php`.
- Created `tests/Feature/EmployeePhotoTest` to validate signed URL behaviour and unauthorized access blocks.
- Added Playwright spec `tests/playwright/employee-photos.spec.ts` confirming photo rendering in UI.

## Verification
- `php artisan test --filter=EmployeePhotoTest` ✅
- `npx playwright test tests/playwright/employee-photos.spec.ts` ✅
- Manual upload + view check on employee profile and list confirms images display with temporary URL parameters.

## Rollback Plan
1. Revert commit containing the signed URL implementation (`git revert <commit_hash>`).
2. Clear caches (`php artisan config:clear`, `php artisan route:clear`, `php artisan view:clear`).
3. Verify employee photos fall back to previous behaviour.
