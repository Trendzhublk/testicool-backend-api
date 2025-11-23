<?php

namespace Database\Seeders;

use App\Models\ShippingRate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShippingRateSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $dhl = \App\Models\ShippingAgent::where('name', 'DHL')->first();
        $fedex = \App\Models\ShippingAgent::where('name', 'FedEx')->first();

        $rates = [
            // United Kingdom (GB)
            ['code' => 'dhl_express', 'label' => 'DHL Express Worldwide', 'carrier' => 'DHL', 'shipping_agent_id' => $dhl?->id, 'region' => 'uk', 'country_code' => 'GB', 'rate_basis' => 'country', 'charge_type' => 'flat', 'tax_percent' => 0, 'amount' => 18, 'currency' => 'GBP', 'priority' => 10],
            ['code' => 'fedex_priority', 'label' => 'FedEx International Priority', 'carrier' => 'FedEx', 'shipping_agent_id' => $fedex?->id, 'region' => 'uk', 'country_code' => 'GB', 'rate_basis' => 'country', 'charge_type' => 'flat', 'tax_percent' => 0, 'amount' => 22, 'currency' => 'GBP', 'priority' => 20],

            // United States (US)
            ['code' => 'dhl_express', 'label' => 'DHL Express Worldwide', 'carrier' => 'DHL', 'shipping_agent_id' => $dhl?->id, 'region' => 'us', 'country_code' => 'US', 'rate_basis' => 'country', 'charge_type' => 'flat', 'tax_percent' => 0, 'amount' => 22, 'currency' => 'USD', 'priority' => 10],
            ['code' => 'fedex_priority', 'label' => 'FedEx International Priority', 'carrier' => 'FedEx', 'shipping_agent_id' => $fedex?->id, 'region' => 'us', 'country_code' => 'US', 'rate_basis' => 'country', 'charge_type' => 'flat', 'tax_percent' => 0, 'amount' => 26, 'currency' => 'USD', 'priority' => 20],

            // Canada (CA) - using USD rates as placeholder
            ['code' => 'dhl_express', 'label' => 'DHL Express Worldwide', 'carrier' => 'DHL', 'shipping_agent_id' => $dhl?->id, 'region' => 'us', 'country_code' => 'CA', 'rate_basis' => 'country', 'charge_type' => 'flat', 'tax_percent' => 0, 'amount' => 24, 'currency' => 'USD', 'priority' => 10],
            ['code' => 'fedex_priority', 'label' => 'FedEx International Priority', 'carrier' => 'FedEx', 'shipping_agent_id' => $fedex?->id, 'region' => 'us', 'country_code' => 'CA', 'rate_basis' => 'country', 'charge_type' => 'flat', 'tax_percent' => 0, 'amount' => 28, 'currency' => 'USD', 'priority' => 20],

            // Australia (AU)
            ['code' => 'dhl_express', 'label' => 'DHL Express Worldwide', 'carrier' => 'DHL', 'shipping_agent_id' => $dhl?->id, 'region' => 'au', 'country_code' => 'AU', 'rate_basis' => 'country', 'charge_type' => 'flat', 'tax_percent' => 0, 'amount' => 25, 'currency' => 'AUD', 'priority' => 10],
            ['code' => 'fedex_priority', 'label' => 'FedEx International Priority', 'carrier' => 'FedEx', 'shipping_agent_id' => $fedex?->id, 'region' => 'au', 'country_code' => 'AU', 'rate_basis' => 'country', 'charge_type' => 'flat', 'tax_percent' => 0, 'amount' => 30, 'currency' => 'AUD', 'priority' => 20],
        ];

        ShippingRate::truncate();

        foreach ($rates as $rate) {
            $rate['currency_rates'] = [
                [
                    'currency' => $rate['currency'],
                    'amount' => $rate['amount'],
                    'tax_percent' => $rate['tax_percent'],
                ],
            ];

            ShippingRate::create($rate);
        }
    }
}
