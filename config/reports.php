<?php

return [
    'use_new_exports' => env('REPORTS_USE_NEW_EXPORTS', false),
    'queue_connection' => env('REPORTS_EXPORT_QUEUE_CONNECTION', env('QUEUE_CONNECTION', 'database')),
    'queue_name' => env('REPORTS_EXPORT_QUEUE', 'reports'),
    'force_queue' => env('REPORTS_FORCE_QUEUE', false),
    'cache_ttl' => (int) env('REPORTS_EXPORT_CACHE_TTL', 3600),

    'definitions' => [
        'employee-list' => [
            'slug' => 'employee-list',
            'key' => 'reports.employee.list',
            'name' => 'Employee Directory',
            'description' => 'Employee roster with department and position filters',
            'permission' => 'reports.view',
            'adapter' => App\Reports\Adapters\EmployeeListReport::class,
            'queue_threshold' => 1000,
            'formats' => ['excel', 'pdf'],
        ],
    ],
];

