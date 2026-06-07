<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Technology', 'icon' => 'cpu', 'color' => '#7C3AED'],
            ['name' => 'Music', 'icon' => 'music', 'color' => '#EC4899'],
            ['name' => 'Business', 'icon' => 'briefcase', 'color' => '#F59E0B'],
            ['name' => 'Education', 'icon' => 'book-open', 'color' => '#10B981'],
            ['name' => 'Lifestyle', 'icon' => 'sparkles', 'color' => '#06B6D4'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                [
                    'name' => $category['name'],
                    'slug' => Str::slug($category['name']),
                    'icon' => $category['icon'],
                    'color' => $category['color'],
                ]
            );
        }
    }
}
