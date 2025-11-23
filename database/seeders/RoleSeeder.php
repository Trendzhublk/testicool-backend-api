<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $agentRole = Role::firstOrCreate(['name' => 'Sales Agent', 'guard_name' => 'web']);

        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'country_code' => 'US',
            ]
        );
        $admin->syncRoles([$adminRole]);

        $agent = User::firstOrCreate(
            ['email' => 'agent@example.com'],
            [
                'name' => 'Sales Agent',
                'password' => Hash::make('password'),
                'country_code' => 'US',
            ]
        );
        $agent->syncRoles([$agentRole]);
    }
}
