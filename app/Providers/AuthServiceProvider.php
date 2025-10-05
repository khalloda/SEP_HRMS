<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\Employee::class => \App\Policies\EmployeePolicy::class,
        \App\Models\Contract::class => \App\Policies\ContractPolicy::class,
        \App\Models\SalaryStructure::class => \App\Policies\SalaryStructurePolicy::class,
        \App\Models\PayrollRun::class => \App\Policies\PayrollPolicy::class,
        \App\Models\Payslip::class => \App\Policies\PayrollPolicy::class,
        \Spatie\Activitylog\Models\Activity::class => \App\Policies\AuditTrailPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
