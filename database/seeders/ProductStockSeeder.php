<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Database\Seeder;

class ProductStockSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();

        foreach ($products as $product) {
            // Initial stock IN
            ProductStock::create([
                'product_id' => $product->id,
                'quantity' => rand(50, 200),
                'type' => 'in',
                'note' => 'Initial stock',
            ]);

            // Random stock movements
            if (rand(0, 1)) {
                // Some stock OUT (sales)
                ProductStock::create([
                    'product_id' => $product->id,
                    'quantity' => rand(5, 20),
                    'type' => 'out',
                    'note' => 'Sales transaction',
                ]);
            }

            if (rand(0, 1)) {
                // Additional stock IN (restock)
                ProductStock::create([
                    'product_id' => $product->id,
                    'quantity' => rand(20, 50),
                    'type' => 'in',
                    'note' => 'Restock',
                ]);
            }
        }

        $this->command->info('Product stocks seeded successfully!');
    }
}