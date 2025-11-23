<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Size;
use App\Models\SizeChart;

class SizeChartSeeder extends Seeder
{
    public function run(): void
    {
        $chart = [
            'XS' => ['CM' => [64, 69], 'INCH' => [25, 27]],
            'S'  => ['CM' => [71, 76], 'INCH' => [28, 30]],
            'M'  => ['CM' => [79, 86], 'INCH' => [31, 34]],
            'L'  => ['CM' => [89, 97], 'INCH' => [35, 38]],
            'XL' => ['CM' => [99, 104], 'INCH' => [39, 41]],
            'XXL' => ['CM' => [107, 112], 'INCH' => [42, 44]],
        ];

        foreach ($chart as $sizeName => $units) {
            $size = Size::where('name', $sizeName)->first();

            foreach ($units as $unit => [$min, $max]) {
                SizeChart::updateOrCreate(
                    ['size_id' => $size->id, 'unit' => $unit],
                    ['min_value' => $min, 'max_value' => $max]
                );
            }
        }
    }
}
