<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::truncate();

        $products = [
            [
                'name' => 'Laptop ASUS X441NA',
                'description' => 'Laptop ASUS core i5, 8GB RAM, 512GB SSD',
                'price' => 7500000,
                'cost' => 6500000,
                'stock' => 15,
                'min_stock' => 3,
                'sku' => 'LP-ASUS-001',
                'barcode' => '1234567890123',
                'category_id' => 1,
                'is_active' => true
            ],
            [
                'name' => 'Mouse Wireless Logitech',
                'description' => 'Mouse wireless ergonomis',
                'price' => 250000,
                'cost' => 180000,
                'stock' => 50,
                'min_stock' => 10,
                'sku' => 'MW-LOGI-001',
                'barcode' => '1234567890124',
                'category_id' => 1,
                'is_active' => true
            ],
            [
                'name' => 'Panci Stainless Steel',
                'description' => 'Panci masak stainless steel 24cm',
                'price' => 120000,
                'cost' => 80000,
                'stock' => 30,
                'min_stock' => 5,
                'sku' => 'PN-STL-001',
                'barcode' => '1234567890125',
                'category_id' => 2,
                'is_active' => true
            ],
            [
                'name' => 'Buku Tulis A4',
                'description' => 'Buku tulis 58 lembar',
                'price' => 5000,
                'cost' => 3000,
                'stock' => 200,
                'min_stock' => 50,
                'sku' => 'BT-A4-001',
                'barcode' => '1234567890126',
                'category_id' => 3,
                'is_active' => true
            ],
            [
                'name' => 'Kopi Kapal Api',
                'description' => 'Kopi bubuk 250gr',
                'price' => 15000,
                'cost' => 10000,
                'stock' => 100,
                'min_stock' => 20,
                'sku' => 'KP-KAPI-001',
                'barcode' => '1234567890127',
                'category_id' => 4,
                'is_active' => true
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}