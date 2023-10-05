<?php

namespace Database\Seeders;

use App\Models\PrintingMaterial;
use App\Models\ProductCommonContent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChuruataDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            LicenceSeeder::class,
            CategorySeeder::class,
            TagSeeder::class,
            PrintingMaterialSeeder::class,
            PrintSettingSeeder::class,
            ProductCommonContentSeeder::class,
        ]);
    }
}
