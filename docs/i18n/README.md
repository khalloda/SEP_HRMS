# i18n Guidelines

- Use namespaced keys (auth.*, employees.*, payroll.*, reports.*, common.*).
- Prefer `__('common.save')` etc. for common UI terms.
- Arabic files: `resources/lang/ar/*.php`. English: `resources/lang/en/*.php`.
- Switch locale via `/language/{locale}`. Fallback per `config('app.fallback_locale')`.
- For model labels with `name_en`/`name_ar`, use `ComponentName::display()`.
