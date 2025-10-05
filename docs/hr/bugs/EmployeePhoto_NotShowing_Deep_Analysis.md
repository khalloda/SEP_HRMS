# Employee Photo Not Showing — Deep Analysis

## Summary
- **Observed Issue**: Uploaded employee profile photos fail to appear in both the employee list and profile views; UI shows broken image or fallback avatar regardless of upload success.
- **Scope**: Employee detail page (`resources/views/employees/show.blade.php`), employee list (`resources/views/employees/index.blade.php`), `EmployeeController@servePhoto`, `Employee` model accessors, private storage configuration.
- **Finding**: Photos are stored under the `private` disk and served via `EmployeeController@servePhoto`, which builds routes like `/employees/{id}/photo`. The policy on this route requires `view` permission (`Gate::authorize('view', $employee)`), but the public-facing thumbnail requests bypass auth (e.g., listing page fetched by browser without session), resulting in 403/redirect and broken images. Additionally, `hasPhoto()` uses `Storage::disk('private')->exists`, meaning even valid files return false when symbolic link to `storage` is expected for UI fetch.

## Investigation Steps
1. **Reviewed Model Logic**
   - `Employee::getPhotoUrlAttribute()` returns `route('employee.photo', ['employee' => $this->id])` when `photo_path` is set.
   - `Employee::hasPhoto()` checks the private disk.
   - Default fallback uses `ui-avatars.com` and should render for missing photos.
2. **Controller Review**
   - `EmployeeController@servePhoto` authorizes `view` on the employee, then reads from `storage_path('app/private/...')` and returns binary file.
   - Route `employees/{employee}/photo` is registered without explicit middleware but `Gate::authorize` enforces permission. For employee list thumbnails (public list view) the user has a session, but thumbnails in emails or caches will fail.
3. **View Rendering**
   - `employees/index.blade.php` and `employees/show.blade.php` both rely on `$employee->photo_url`.
   - When the controller aborts with 403 or 404, browsers display broken image. No fallback logic triggers.
4. **Storage Verification**
   - Inspected `storage/app/private/employee_photos/` and confirmed photo files exist. Sizes and names match expected format `<code>_<timestamp>.<ext>`.
5. **Route Accessibility Test**
   - Accessed `https://.../employees/1/photo` manually (with expected data) and received redirect to login when unauthenticated, 403 when lacking `view` permission. For thumbnails, this results in failed image loads.
6. **Symlink & URL**
   - Standard `php artisan storage:link` only exposes `storage/app/public`. Employee photos are stored under `storage/app/private`, but served via controller, so no symlink should exist. Access requires authenticated request to route.
7. **Logs Review**
   - No direct errors logged for photo route; browser dev tools show 302/403 responses for image requests during manual testing.

## Root Cause
- Employee photos are stored on the private disk and routed through an authorization-protected endpoint. Browsers fetching thumbnails on list/detail pages issue GET requests without CSRF tokens but with session cookies. However, `Gate::authorize('view', $employee)` fails for roles lacking explicit permission, and for guest contexts (emails). This causes the controller to return 403/404, making the `<img>` tags break. Additionally, when `hasPhoto()` returns false due to storage visibility mismatch, UI fallback is triggered unexpectedly.

## Impact Assessment
- **User Roles Affected**: HR staff, managers, and any authenticated users viewing employees without proper policy alignment. Emails or external embeds always fail.
- **Functional Impact**: Profile and directory photos do not render; reduces usability and branding consistency.
- **Security**: Photos are appropriately stored in private disk; issue is access control, not data leak.
- **Scope of Fix**: Adjust authorization for photo serving, or move photos to signed URLs. Ensure `hasPhoto()` and fallback behave consistently.

## Resolved Questions
- All authenticated users with the `employees.view` permission must see employee photos. Authorization should align with that capability rather than individual `view` checks.
- Best practice is to continue storing files privately while exposing them via short-lived signed URLs generated for authorized viewers. This avoids overly permissive public exposure and reduces repeated authorization failures.

## Residual Risks
- Signed URLs must be cached or refreshed thoughtfully to avoid expired links causing broken images during active sessions.
- Need to confirm browser caching headers so that rotating URLs do not thrash caches or leak data via shared links.

## Next Steps
- Update the implementation plan to incorporate the clarified requirements (permission scope and signed URL strategy).
- Execute the plan, ensuring rollback strategy is documented and tests cover the signed URL flow.
