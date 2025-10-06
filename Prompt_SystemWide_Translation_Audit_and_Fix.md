# Codex/Auto Prompt — System‑Wide Translation Coverage (EN/AR) Audit & Fix Plan

**Mode:** Auto (follow `Agent_Rules.md` — commits, docs, tests, rollback, **no Codacy**).  
**Scope:** Non‑destructive i18n improvements across the app (Blade, controllers, validation, exports, PDFs).  
**Branch:** `feat/i18n-coverage-ar` (create if missing).

---

## Context (from investigation)
- Translation engine: **Laravel localization**.
- Storage: `resources/lang/en/*.php`, `resources/lang/ar/*.php` (and optional `*.json`).
- Switching: `SetLocale` middleware via `/language/{locale}` → `app()->setLocale($locale)` with `config('app.fallback_locale')`.
- Usage: `__('key')`, `@lang('key')`, `trans_choice()` in Blade/PHP.
- RTL: conditionally set `dir="rtl"` when `locale === 'ar'`, Arabic‑safe fonts in PDF.
- Model labels (e.g., salary component names): handled via helper `ComponentName::display` (name_en/name_ar).

We need to make **the whole UI respect Arabic** (strings, buttons, menus, form labels, validation, exports, PDFs), and ensure **fallbacks** work.

---

## Goals
1. Achieve **>95% translation coverage** for visible strings in AR/EN.
2. Standardize translation **keys** and **namespaces**.
3. Ensure **validation**, **pagination**, **auth**, and **vendor** messages show in Arabic.
4. Ensure **PDF/Excel exports** render Arabic correctly (fonts/RTL).
5. Add **automated checks** (Playwright + unit) to prevent regressions.

---

## Rules
- Non‑destructive: do not alter DB schema. Adding language files/keys is allowed.
- Use **namespaced keys** by domain: e.g., `employees.index.title`, `payroll.history.gross_total`.
- Keep translations **short and clear**; avoid embedding HTML where possible.
- Do not rename existing keys without a deprecation shim (map old → new for a transition period).
- Arabic keys must be **human‑reviewable** (no machine‑garbage).

---

## Phased Plan (atomic commits)

### Phase 1 — Inventory & Key Strategy
1. **Scan** Blade, PHP, JS (if applicable) for hard‑coded English strings. Create an inventory in:
   - `docs/i18n/i18n_Inventory.md` (path, snippet, proposed key).
2. Define **key namespaces** per module:
   - `auth.*`, `employees.*`, `payroll.*`, `reports.*`, `common.*` (buttons, labels, generic).
3. Create base files:
   - `resources/lang/en/{auth,employees,payroll,reports,common}.php`
   - `resources/lang/ar/{auth,employees,payroll,reports,common}.php`
   - (Optional) `resources/lang/en.json`, `resources/lang/ar.json` for ad‑hoc strings.
4. Add helper to resolve **yes/no**, **on/off**, **enabled/disabled** via `common.*` keys.

**Deliverables:** inventory doc + initial lang files scaffold.  
**Tests:** none (scaffold).

---

### Phase 2 — Replace Hard‑coded Strings
1. Replace Blade/PHP literals with `__('namespace.key')` or `@lang()`.
2. Centralize button/label text in `common.*` (e.g., `common.save`, `common.cancel`, `common.edit`).
3. Update **menus**, **breadcrumbs**, **empty states**, **alerts/toasts**.
4. Add **trans_choice** where plurals appear (e.g., “1 result” vs “2 results”).

**Deliverables:** refactors across views/controllers.  
**Tests:** Playwright smoke test: toggle EN/AR and verify 10+ critical screens contain AR text, not English literals.

---

### Phase 3 — Validation & System Messages
1. Ensure `resources/lang/ar/validation.php` exists and is complete; merge Laravel’s official AR set if needed.
2. Translate **auth**/password messages: `resources/lang/ar/auth.php`, `passwords.php`.
3. Pagination / Paginator:
   - Publish vendor views if necessary; translate labels via `lang`.
4. **Custom validation lines** (FormRequest messages) → move to lang files under module namespaces.

**Deliverables:** translated validation/auth lines.  
**Tests:** unit tests for a sample FormRequest that asserts AR messages when locale=ar.

---

### Phase 4 — Reports, PDF, Excel
1. Ensure exporters use translation keys (no hard‑coded English in headers/labels).
2. PDF:
   - Verify Arabic fonts embedded (e.g., Amiri, Cairo, Noto Naskh); configure in mPDF/Dompdf.
   - Ensure RTL styles (`direction: rtl; unicode-bidi: bidi-override;`) where needed.
3. Excel:
   - Header labels via lang keys.
   - Date/number formats locale‑aware if possible.
4. Add “Language” column in exports **only** if useful; otherwise keep localized headers.

**Deliverables:** i18n’d exports.  
**Tests:** add export tests that assert translated headers for AR; file not empty.

---

### Phase 5 — Navigation, RTL, and UX Polish
1. Ensure layout sets `lang` and `dir` attributes correctly (`html lang="ar" dir="rtl"`).
2. Adjust spacing/alignment CSS for RTL (margin/padding swaps as needed). Prefer utility classes if using Bootstrap/Tailwind RTL plugins.
3. Icons that imply direction (chevrons/arrows) should flip in RTL where appropriate.

**Deliverables:** RTL polish.  
**Tests:** Playwright visual assertions for a few pages AR vs EN (sanity).

---

### Phase 6 — Automation & Guardrails
1. Add a **lint script** to detect hard‑coded English in Blade/PHP:
   - Grep for patterns and exclude lang files.
2. Add a CI check that fails if new English literals are introduced in Blade.
3. Add a tiny **i18n readme**: `docs/i18n/README.md` explaining how to add new keys.

**Deliverables:** scripts + docs.  
**Tests:** CI passes with current code; try an intentional literal to confirm failure locally.

---

## Implementation Notes
- Keep `ComponentName::display()` for name_en/name_ar (already handled); reuse it in salary pages/exports.
- Prefer `__('common.save')` etc. to avoid duplication.
- Where strings contain variables, use placeholders: `__('employees.show.title', ['name' => $employee->full_name])`.
- For JSON translations (front‑end), use `resources/lang/{locale}.json` and `__()` from JS if needed.

---

## Acceptance Criteria
- Toggle to AR → **no major English literals** remain on core flows (employees, payroll, reports).
- Validation/auth/pagination messages are Arabic.
- Exports (PDF/Excel) have translated headers and render Arabic text correctly.
- Playwright smoke passes in EN and AR.
- CI/lint prevents new hard‑coded English literals.

---

## Reply Format (each step)
- **status** — one line
- **files changed**
- **commit hash**
- **NEXT** — next small action
- End with **“READY FOR NEXT?”** when awaiting approval
