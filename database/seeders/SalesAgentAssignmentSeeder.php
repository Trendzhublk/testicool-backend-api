<?php

namespace Database\Seeders;

use App\Models\SalesAgentAssignment;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SalesAgentAssignmentSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $agent = User::where('email', 'agent@example.com')->first();

        if ($agent) {
            SalesAgentAssignment::updateOrCreate(
                ['user_id' => $agent->id],
                ['country_code' => $agent->country_code ?? 'US']
            );
        }
    }
}
