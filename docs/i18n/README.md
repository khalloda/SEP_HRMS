### i18n Guidelines

- Use namespaced keys by domain (e.g., `employees.show.title`, `payroll.exports.employee_code`).
- Put generic UI labels under `resources/lang/{locale}/common.php`.
- Prefer placeholders for variables: `__('employees.show.title', ['name' => $employee->display_name])`.
- For front-end/Blade, use `__('key')` or `@lang('key')`.
- For exports, centralize headers in lang files and reference keys from controllers/services.
- For PDFs, ensure RTL via `dir="rtl"` and use DejaVu Sans (mPDF autoLangToFont is enabled).

Linting
- Detect hard-coded English: `php artisan i18n:lint-hardcoded`
- Exit code non-zero if any violations found.

Adding new keys
- English in `resources/lang/en/*.php`.
- Arabic in `resources/lang/ar/*.php`.
- Keep keys short and reusable; avoid embedding HTML in translations.
