<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed 5 product categories
        $categories = [
            [
                'name' => 'Electronics',
                'description' => 'Electronic devices and gadgets',
            ],
            [
                'name' => 'Fashion',
                'description' => 'Clothing and fashion accessories',
            ],
            [
                'name' => 'Books',
                'description' => 'Books and stationery',
            ],
            [
                'name' => 'Home & Living',
                'description' => 'Home appliances and living essentials',
            ],
            [
                'name' => 'Sports',
                'description' => 'Sports equipment and accessories',
            ],
        ];

        $categoryIds = [];
        foreach ($categories as $category) {
            $cat = ProductCategory::updateOrCreate($category);
            $categoryIds[] = $cat->id;
        }

        // Seed 50 products
        for ($i = 1; $i <= 50; $i++) {
            Product::updateOrCreate([
                'name' => 'Product ' . $i,
                'description' => 'Description for Product ' . $i,
                'price' => rand(10000, 1000000),
                'stock' => rand(1, 100),
                'image' => 'images/product_' . $i . '.jpg',
                'product_category_id' => $categoryIds[array_rand($categoryIds)],
            ]);
        }
    }
}
