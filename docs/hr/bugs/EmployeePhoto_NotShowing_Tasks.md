# Employee Photo Not Showing — Tasks

## Work Breakdown
- **Task 1**: Implement signed URL generation for employee photos in the model/service layer.
- **Task 2**: Update controller logic to authorize using `employees.view` and return signed URLs or default avatar.
- **Task 3**: Adjust Blade views to handle new URL structure and fallback logic gracefully.
- **Task 4**: Add Laravel feature tests covering signed URL access, fallback behavior, and permission boundaries.
- **Task 5**: Create Playwright test ensuring photos render for uploaded and default states.
- **Task 6**: Update documentation/runbooks with signed URL behavior, TTL, and troubleshooting steps.
- **Task 7**: Perform manual verification (upload photo, check expiry, fallback) and record results.

## Tracking Table
| Task | Owner | Status | Notes |
|------|-------|--------|-------|
| Task 1 | TBD | Not Started | Generate temporary URLs with caching/TTL |
| Task 2 | TBD | Not Started | Align controller auth with `employees.view` |
| Task 3 | TBD | Not Started | Ensure UI gracefully handles expired URLs |
| Task 4 | TBD | Not Started | Cover success + unauthorized cases |
| Task 5 | TBD | Not Started | Validate frontend rendering across views |
| Task 6 | TBD | Not Started | Document deployment + rollback guidance |
| Task 7 | TBD | Not Started | Manual QA checklist |
