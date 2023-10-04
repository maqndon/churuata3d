<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Home Solutions',
            'Office Solutions',
            'Parametric Designs',
            '3D Models'
        ];

        foreach ($categories as $category) {
            \App\Models\Category::firstOrNew()->create([
                'name' => $category,
                'slug' => Str::of($category)->slug('-'),
            ]);
        }
    }
}
