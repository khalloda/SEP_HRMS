<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Employee;
use Spatie\Permission\Models\Role;

class DefaultUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create HR Admin Manager
        $hrAdmin = User::create([
            'name' => 'HR Administrator',
            'email' => 'hr@sarieldin.com',
            'password' => Hash::make('password123'),
        ]);
        $hrAdmin->assignRole('HR_Admin_Manager');

        // Create Accounting Manager
        $accountingManager = User::create([
            'name' => 'Accounting Manager',
            'email' => 'accounting@sarieldin.com',
            'password' => Hash::make('password123'),
        ]);
        $accountingManager->assignRole('Accounting_Manager');

        // Create IT Admin
        $itAdmin = User::create([
            'name' => 'IT Administrator',
            'email' => 'it@sarieldin.com',
            'password' => Hash::make('password123'),
        ]);
        $itAdmin->assignRole('IT_Admin');

        // Create HR Coordinator
        $hrCoordinator = User::create([
            'name' => 'HR Coordinator',
            'email' => 'hrcoord@sarieldin.com',
            'password' => Hash::make('password123'),
        ]);
        $hrCoordinator->assignRole('HR_Coordinator');

        // Create a demo employee user (not linked to employee record yet)
        $demoUser = User::create([
            'name' => 'Demo Employee',
            'email' => 'demo@sarieldin.com',
            'password' => Hash::make('password123'),
        ]);
        $demoUser->assignRole('Employee');

        $this->command->info('Default users created successfully!');
        $this->command->info('');
        $this->command->info('Login credentials:');
        $this->command->info('HR Admin: hr@sarieldin.com / password123');
        $this->command->info('Accounting: accounting@sarieldin.com / password123');
        $this->command->info('IT Admin: it@sarieldin.com / password123');
        $this->command->info('HR Coordinator: hrcoord@sarieldin.com / password123');
        $this->command->info('Demo Employee: demo@sarieldin.com / password123');
        $this->command->info('');
        $this->command->warn('Please change these passwords in production!');
    }
}