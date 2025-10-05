<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Schema;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Core admin
            'users.manage', 'roles.manage', 'permissions.manage',
            // Employees
            'employees.view', 'employees.manage',
            // Contracts
            'contracts.view', 'contracts.manage',
            // Documents
            'documents.view', 'documents.manage',
            // Letters
            'letters.view', 'letters.manage', 'letters.generate',
            // Reports
            'reports.view',
            // Payroll & Attendance (optional)
            'payroll.view', 'payroll.manage',
            'attendance.view', 'attendance.manage',
        ];

        $permHasDisplay = Schema::hasColumn('permissions', 'display_name');
        foreach ($permissions as $p) {
            if ($permHasDisplay) {
                // Ensure a human-friendly display name if column exists and is non-nullable
                $display = ucwords(str_replace(['.', '_'], [' ', ' '], $p));
                Permission::query()->updateOrCreate(
                    ['name' => $p, 'guard_name' => 'web'],
                    ['display_name' => $display]
                );
            } else {
                Permission::findOrCreate($p, 'web');
            }
        }

        // Create roles if missing
        $roleHasDisplay = Schema::hasColumn('roles', 'display_name');
        $createRole = function (string $name) use ($roleHasDisplay) {
            if ($roleHasDisplay) {
                $display = ucwords(str_replace(['_', '-'], ' ', $name));
                return Role::query()->updateOrCreate(
                    ['name' => $name, 'guard_name' => 'web'],
                    ['display_name' => $display]
                );
            }
            return Role::findOrCreate($name, 'web');
        };

        $itAdmin  = $createRole('IT_Admin');
        $hrAdmin  = $createRole('HR_Admin_Manager');
        $hrCoord  = $createRole('HR_Coordinator');
        $acctMgr  = $createRole('Accounting_Manager');
        $employee = $createRole('Employee');

        // Assign permissions
        $itAdmin->syncPermissions($permissions); // full access

        $hrAdminPerms = [
            'users.manage', 'roles.manage', // can manage users/roles
            'employees.view','employees.manage',
            'contracts.view','contracts.manage',
            'documents.view','documents.manage',
            'letters.view','letters.manage','letters.generate',
            'reports.view',
            'attendance.view','attendance.manage',
            'payroll.view',
        ];
        $hrAdmin->syncPermissions($hrAdminPerms);

        $hrCoordPerms = [
            'employees.view','employees.manage',
            'contracts.view','contracts.manage',
            'documents.view','documents.manage',
            'letters.view','letters.generate',
            'reports.view',
            'attendance.view',
        ];
        $hrCoord->syncPermissions($hrCoordPerms);

        $acctPerms = [
            'reports.view', 'payroll.view',
            'documents.view',
        ];
        $acctMgr->syncPermissions($acctPerms);

        $employee->syncPermissions([
            'reports.view', // basic dashboard/reports if applicable
        ]);
    }
}
