<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Color;

class ColorSeeder extends Seeder
{
    public function run(): void
    {
        $colors = [
            ['name' => 'Black', 'hex' => '#3C3C3C'],
            ['name' => 'Green', 'hex' => '#2F4F4F'],
            ['name' => 'Blue', 'hex' => '#1E3A5F'],
        ];

        foreach ($colors as $c) {
            Color::updateOrCreate(['name' => $c['name']], $c);
        }
    }
}
