<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Create main admin account
        User::updateOrCreate(
            ['email' => 'admin@eldera.com'],
            [
                'name' => 'ELDERA Admin',
                'email' => 'admin@eldera.com',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );

        // Create additional admin accounts
        $admins = [
            [
                'name' => 'OSCA Manager',
                'email' => 'osca@eldera.com',
                'password' => 'osca123',
            ],
            [
                'name' => 'System Administrator',
                'email' => 'sysadmin@eldera.com',
                'password' => 'sysadmin123',
            ],
        ];

        foreach ($admins as $admin) {
            User::updateOrCreate(
                ['email' => $admin['email']],
                [
                    'name' => $admin['name'],
                    'email' => $admin['email'],
                    'password' => Hash::make($admin['password']),
                    'email_verified_at' => now(),
                ]
            );
        }

        $this->command->info('Admin accounts created successfully!');
        $this->command->info('Main Admin: admin@eldera.com / admin123');
        $this->command->info('OSCA Manager: osca@eldera.com / osca123');
        $this->command->info('System Admin: sysadmin@eldera.com / sysadmin123');
    }
}

























