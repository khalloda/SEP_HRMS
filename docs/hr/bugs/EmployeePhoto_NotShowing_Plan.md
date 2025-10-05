# Employee Photo Not Showing — Plan

## Proposed Fix
- Generate signed temporary URLs (e.g., 10–15 minute TTL) for employee photos stored on the `private` disk using `Storage::disk('private')->temporaryUrl()` and expose them through a controller method that verifies `employees.view` authorization for the requesting user.
- Adjust `Employee::photo_url` accessor to return the signed URL (with caching to avoid regenerating on every call) when the authenticated user holds the `employees.view` permission; otherwise fall back to the default avatar.
- Update `EmployeeController@servePhoto` (or replace with a dedicated signed URL controller) to align authorization with `employees.view` capability rather than the stricter per-employee `view` policy.
- Ensure `Employee::hasPhoto()` checks file existence correctly on the `private` disk and triggers default avatar only when the file truly is missing.
- Document requirements for rotating application key affecting signed URLs and ensure `storage:link` guidance remains unchanged (no public exposure of private files).

## Rollback Strategy
- Revert controller/model changes via `git revert` or by restoring previous commit.
- Clear caches (`php artisan route:clear`, `php artisan config:clear`, `php artisan view:clear`) to flush cached signed URLs.
- Confirm feature flag or configuration is toggled off if temporary URLs introduce issues.

## Testing
- Feature test verifying authenticated users with `employees.view` can retrieve signed photo URLs and see the rendered image; ensure unauthorized users receive fallback avatar.
- Playwright E2E test validating that employee profile and list views display uploaded photo and default avatar appropriately.
- Manual verification by uploading a photo, observing signed URL generation duration, and confirming fallback behavior once URL expires or file removed.
