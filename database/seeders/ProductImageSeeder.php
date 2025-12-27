<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();

        foreach ($products as $product) {
            // Primary image
            ProductImage::create([
                'product_id' => $product->id,
                'image_url' => 'https://via.placeholder.com/500x500/FF0000/FFFFFF?text=' . urlencode($product->name),
                'is_primary' => true,
            ]);

            // Additional images
            for ($i = 2; $i <= 3; $i++) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url' => 'https://via.placeholder.com/500x500/0000FF/FFFFFF?text=Image+' . $i,
                    'is_primary' => false,
                ]);
            }
        }

        $this->command->info('Product images seeded successfully!');
    }
}