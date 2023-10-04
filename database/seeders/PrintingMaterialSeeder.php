<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PrintingMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $materials = [
            ['name' => 'PLA',  'nozzle' => 0.4, 'min_hot_bed_temp' => 0, 'max_hot_bed_temp' => 60],
            ['name' => 'PLA+',  'nozzle' => 0.4, 'min_hot_bed_temp' => 0, 'max_hot_bed_temp' => 60],
            ['name' => 'Wood PLA',  'nozzle' => 0.8, 'min_hot_bed_temp' => 0, 'max_hot_bed_temp' => 60],
            ['name' => 'PETG',  'nozzle' => 0.4, 'min_hot_bed_temp' => 60, 'max_hot_bed_temp' => 80],
        ];

        foreach ($materials as $material) {
            \App\Models\PrintingMaterial::firstOrNew()->create([
                'name' => $material['name'],
                'nozzle_size' => $material['nozzle'],
                'min_hot_bed_temp' => $material['min_hot_bed_temp'],
                'max_hot_bed_temp' => $material['max_hot_bed_temp']
            ]);
        }
    }
}
