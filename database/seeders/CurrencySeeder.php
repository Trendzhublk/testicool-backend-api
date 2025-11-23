<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Currency;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        $currencies = [
            ['code' => 'USD', 'symbol' => '$', 'rate_to_base' => 1, 'is_default' => true],
            ['code' => 'EUR', 'symbol' => '€', 'rate_to_base' => 0.92, 'is_default' => false],
            ['code' => 'GBP', 'symbol' => '£', 'rate_to_base' => 0.78, 'is_default' => false],
        ];

        foreach ($currencies as $c) {
            Currency::updateOrCreate(['code' => $c['code']], $c);
        }
    }
}
