# Salary History PDF — Arabic/RTL QA Checklist

## Pre‑checks
- Ensure `APP_LOCALE=ar` and UI language switched to Arabic.
- Confirm `USE_SALARY_STRUCTURE_HISTORY=true`.

## Visual
- Header shows company name and report title in Arabic; alignment mirrors RTL.
- Date line renders Arabic numerals/text as per locale settings.
- Table headers are RTL; columns keep their logical order and borders.
- Text wraps correctly inside cells; no glyph fallback squares.
- Footer appears on each page with page X of Y.

## Content
- Period rows list Effective From/To dates correctly for Arabic locale.
- Earnings and Deductions breakdown lists render fully without clipping.
- Totals (Gross/Net) are right-aligned and visible only to authorized roles.

## Export
- PDF opens in common readers; selectable text; no cut-off margins.
- Long history paginates without orphaned headers; zebra striping remains consistent.

## Notes
- Font: DejaVu Sans for full Arabic coverage.
- If any truncation occurs, increase margins in `employees/salary-history/pdf.blade.php`.
