<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PrintSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            ['print_strength' => 'hollow', 'resolution' => 0.2, 'infill' => 0, 'top_layers' => 3, 'bottom_layers' => 3, 'walls' => 3, 'speed' => 50],
            ['print_strength' => 'low', 'resolution' => 0.2, 'infill' => 15, 'top_layers' => 3, 'bottom_layers' => 3, 'walls' => 3, 'speed' => 45],
            ['print_strength' => 'medium', 'resolution' => 0.25, 'infill' => 30, 'top_layers' => 3, 'bottom_layers' => 3, 'walls' => 3, 'speed' => 50],
            ['print_strength' => 'high', 'resolution' => 0.3, 'infill' => 50, 'top_layers' => 3, 'bottom_layers' => 3, 'walls' => 3, 'speed' => 50],
            ['print_strength' => 'solid', 'resolution' => 0.3, 'infill' => 100, 'top_layers' => 3, 'bottom_layers' => 3, 'walls' => 3, 'speed' => 50],
        ];

        foreach ($settings as $setting) {
            \App\Models\PrintSetting::firstOrNew()->create([
                'print_strength' => $setting['print_strength'],
                'resolution' => $setting['resolution'],
                'infill' => $setting['infill'],
                'top_layers' => $setting['top_layers'],
                'bottom_layers' => $setting['bottom_layers'],
                'walls' => $setting['walls'],
                'speed' => $setting['speed'],
            ]);
        }
    }
}
