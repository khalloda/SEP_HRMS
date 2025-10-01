<?php

return [
    'enabled' => (bool) env('PAYROLL_V2_ENABLED', false),

    // Calculation engine mode: legacy (eval-based) or new (expression parser)
    'expression_engine' => env('PAYROLL_EXPRESSION_ENGINE', 'legacy'),

    // Toggle queued payroll run processing (introduced in later phases)
    'queue_enabled' => (bool) env('PAYROLL_CALC_QUEUE', false),
];
