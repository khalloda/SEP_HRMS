<?php

return [
    'enabled' => (bool) env('PAYROLL_V2_ENABLED', false),

    // Calculation engine mode: legacy (eval-based) or new (expression parser)
    'expression_engine' => env('PAYROLL_EXPRESSION_ENGINE', 'legacy'),

    // Feature flag: enable conditional formula support in safe engine
    'use_safe_engine_conditionals' => (bool) env('PAYROLL_SAFE_ENGINE_CONDITIONALS', false),

    // Toggle queued payroll run processing (introduced in later phases)
    'queue_enabled' => (bool) env('PAYROLL_CALC_QUEUE', false),

    // Number of employees processed per chunk when running synchronously
    'chunk_size' => (int) env('PAYROLL_CALC_CHUNK', 50),

    // Queue configuration when asynchronous processing is enabled
    'queue_connection' => env('PAYROLL_QUEUE_CONNECTION', env('QUEUE_CONNECTION', 'sync')),
    'queue_name' => env('PAYROLL_QUEUE_NAME'),

    // Supported payroll currencies keyed by display label
    'currencies' => [
        'EGP' => 'EGP',
        'USD' => 'USD',
        'EUR' => 'EUR',
    ],

    // Feature flags for ancillary payroll features
    'use_salary_structure_history' => (bool) env('USE_SALARY_STRUCTURE_HISTORY', false),

    // Runtime evaluation for Salary History (do not persist results)
    'use_salary_history_runtime_eval' => (bool) env('USE_SALARY_HISTORY_RUNTIME_EVAL', true),
    'salary_history_eval_ttl' => (int) env('SALARY_HISTORY_EVAL_TTL', 1800),
];
