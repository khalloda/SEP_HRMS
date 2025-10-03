# Salary Component Formula Conditional Logic Options

## Evaluation Criteria
- **Security**: parser must block arbitrary PHP execution; whitelist tokens & operators only.
- **Backward Compatibility**: existing arithmetic-only formulas must keep working without migration.
- **Authoring UX**: HR admins editing formulas via Blade forms; syntax should be readable and copyable from Excel.
- **Performance**: payroll runs process 50+ employees per chunk; added parsing must remain O(n) per expression, avoid heavy recursion.
- **Internationalisation**: outputs are numeric; ensure no locale-dependent keywords, support Arabic UI (LTR/RTL) without translation burden.

## Option 1 — Ternary Operator (`condition ? true : false`)
- **Pros**: familiar to developers; compact for simple thresholds; reuse parser precedence rules similar to PHP/JS.
- **Cons**: harder for non-technical HR staff; nested ternaries become unreadable; parser complexity increases (associativity handling, `? :` tokens).
- **Security**: safe if parser enforces comparison + boolean operators; must add relational ops (`> < >= <= == !=`) and ensure tokens sanitized.
- **Compatibility**: requires grammar change but arithmetic unaffected; `?` currently invalid so no collisions.
- **Performance**: adds conditional branches in parser; minimal overhead once implemented.

## Option 2 — `IF(condition, value_if_true, value_if_false)`
- **Pros**: Excel-style; HR staff already comfortable; easy to nest; whitespace-insensitive.
- **Cons**: requires function parsing + argument separation respecting parentheses; risk of long nested calls affecting readability.
- **Security**: must whitelist `IF` function and validate argument count; condition still needs comparison ops.
- **Compatibility**: existing formulas remain valid; `IF` currently unused so no conflicts.
- **Performance**: recursion for nested IFs but manageable with stack-based evaluation.

## Option 3 — `CASE ... WHEN` Syntax
- **Pros**: clear for tiered brackets; mirrors SQL CASE.
- **Cons**: verbose for UI entry fields; multi-line expressions tough in single-line text inputs; requires block parser.
- **Security**: similar to IF; but grammar expansion larger; risk of user formatting (line breaks) causing validation issues.
- **Compatibility**: keywords new; parser must ignore case and handle `END` terminator.
- **Performance**: additional lexer states; manageable but more development effort.

## Option 4 — Dedicated Mini DSL
- **Pros**: can tailor to HR language (e.g., `IF GROSS > 10000 THEN ...`); potential for future features (rounding, caps, min/max).
- **Cons**: high implementation/maintenance cost; steep learning curve; requires documentation & linting.
- **Security**: safest if we own grammar; but more surface area to validate.
- **Compatibility**: would need migration tooling or dual-mode support; risk of breaking existing formulas if DSL diverges.
- **Performance**: depends on implementation; potentially slower until optimized.

## Recommendation
Adopt **Option 2 (Excel-style IF function)** with extended operators and boolean expressions.
- Aligns with HR familiarity, easier for tiered tax brackets (`IF(BASIC_SALARY>10000, BASIC_SALARY*0.1, BASIC_SALARY*0.05)`).
- Combine with optional helper functions later (`MIN`, `MAX`, `ROUND`) using same function dispatch pattern.
- Implement parser upgrades incrementally: extend tokenizer for comparison operators, add function handling, evaluate via RPN stack.
- Provide validation & lint messages in UI to guide authors.
