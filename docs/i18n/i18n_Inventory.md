# i18n Inventory — Hard-coded Strings and Proposed Keys

This document tracks identified hard-coded UI strings and the proposed translation keys to migrate them to the localization system. Keep entries terse and focused on visible strings only.

- Updated by: i18n team
- Scope: Blade templates, controllers/messages, exports/PDF headers
- Key style: module.namespace.item (e.g., employees.index.title)

## Legend
- path: file path
- snippet: short visible text snippet
- proposed_key: suggested namespaced key
- notes: optional context

## Findings (initial sample)

- path: resources/views/letters/generate.blade.php
  - snippet: "Letter Template"
  - proposed_key: letters.templates.label
  - notes: Appears in form label

- path: resources/views/letters/generate.blade.php
  - snippet: "Select Template"
  - proposed_key: letters.templates.select

- path: resources/views/letters/generate.blade.php
  - snippet: "Additional Variables"
  - proposed_key: letters.templates.additional_variables

- path: resources/views/letters/generate.blade.php
  - snippet: "English"
  - proposed_key: common.language.en
  - notes: Replace inline language text with common keys

- path: resources/views/payslips/partials/_lines.blade.php
  - snippet: "Component", "Details", "Amount"
  - proposed_key: payroll.payslip.headers.component | payroll.payslip.headers.details | payroll.payslip.headers.amount

- path: resources/views/payslips/partials/_lines.blade.php
  - snippet: "Formula:", "Manual entry", "Restricted", "No components available."
  - proposed_key: payroll.payslip.formula_label | payroll.payslip.manual_entry | payroll.payslip.restricted | payroll.payslip.empty

- path: resources/views/letters/templates/show.blade.php
  - snippet: "Template Details", "Name", "Type", "Category", "Language", "Subject", "Content"
  - proposed_key: letters.templates.details_title | common.name | common.type | common.category | common.language | common.subject | common.content

## Next Steps
- Continue scanning Blade views for literals and append to this list.
- Migrate strings to namespaced keys in new lang files while keeping legacy `hrms.php` for backward compatibility.
- Prefer common.* keys for generic labels/buttons.
