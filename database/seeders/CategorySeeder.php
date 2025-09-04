<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::truncate();

        $categories = [
            ['name' => 'Elektronik', 'description' => 'Produk elektronik'],
            ['name' => 'Peralatan Dapur', 'description' => 'Peralatan masak dan dapur'],
            ['name' => 'ATK', 'description' => 'Alat Tulis Kantor'],
            ['name' => 'Makanan', 'description' => 'Makanan dan minuman'],
            ['name' => 'Pakaian', 'description' => 'Pakaian dan aksesoris'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}