<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            'Home',
            'Office',
            'Kitchen',
            'Bad'
        ];

        foreach ($tags as $tag) {
            \App\Models\Tag::firstOrNew()->create([
                'name' => $tag,
                'slug' => Str::of($tag)->slug('-'),
            ]);
        }
    }
}
