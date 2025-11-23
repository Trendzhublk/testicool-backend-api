<?php

namespace Database\Seeders;

use App\Models\ShippingAgent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShippingAgentSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $agents = [
            ['name' => 'DHL', 'email' => 'ops@dhl.test', 'phone' => '+44 20 7946 0011', 'region' => null, 'country_code' => null, 'priority' => 10],
            ['name' => 'FedEx', 'email' => 'ops@fedex.test', 'phone' => '+1 901 369 3600', 'region' => null, 'country_code' => null, 'priority' => 20],
        ];

        foreach ($agents as $agent) {
            ShippingAgent::updateOrCreate(
                ['name' => $agent['name'], 'region' => $agent['region'], 'country_code' => $agent['country_code']],
                $agent
            );
        }
    }
}
