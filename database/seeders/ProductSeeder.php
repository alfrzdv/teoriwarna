<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();

        $products = [
            // Warna Primer
            [
                'category_id' => $categories->where('name', 'Warna Primer')->first()->id,
                'name' => 'Cat Merah Primary',
                'price' => 150000,
                'description' => 'Cat warna merah primer berkualitas tinggi untuk berbagai media lukis',
                'status' => 'active',
                'stock' => 50,
            ],
            [
                'category_id' => $categories->where('name', 'Warna Primer')->first()->id,
                'name' => 'Cat Kuning Primary',
                'price' => 150000,
                'description' => 'Cat warna kuning primer cerah untuk hasil optimal',
                'status' => 'active',
                'stock' => 45,
            ],
            [
                'category_id' => $categories->where('name', 'Warna Primer')->first()->id,
                'name' => 'Cat Biru Primary',
                'price' => 150000,
                'description' => 'Cat warna biru primer dengan pigmen berkualitas',
                'status' => 'active',
                'stock' => 40,
            ],

            // Warna Sekunder
            [
                'category_id' => $categories->where('name', 'Warna Sekunder')->first()->id,
                'name' => 'Cat Hijau Secondary',
                'price' => 125000,
                'description' => 'Cat warna hijau hasil campuran kuning dan biru',
                'status' => 'active',
                'stock' => 35,
            ],
            [
                'category_id' => $categories->where('name', 'Warna Sekunder')->first()->id,
                'name' => 'Cat Oranye Secondary',
                'price' => 125000,
                'description' => 'Cat warna oranye hasil campuran merah dan kuning',
                'status' => 'active',
                'stock' => 30,
            ],
            [
                'category_id' => $categories->where('name', 'Warna Sekunder')->first()->id,
                'name' => 'Cat Ungu Secondary',
                'price' => 125000,
                'description' => 'Cat warna ungu hasil campuran merah dan biru',
                'status' => 'active',
                'stock' => 25,
            ],

            // Warna Tersier
            [
                'category_id' => $categories->where('name', 'Warna Tersier')->first()->id,
                'name' => 'Cat Merah Oranye Tertiary',
                'price' => 135000,
                'description' => 'Warna tersier kombinasi merah dan oranye',
                'status' => 'active',
                'stock' => 20,
            ],
            [
                'category_id' => $categories->where('name', 'Warna Tersier')->first()->id,
                'name' => 'Cat Kuning Hijau Tertiary',
                'price' => 135000,
                'description' => 'Warna tersier kombinasi kuning dan hijau',
                'status' => 'active',
                'stock' => 15,
            ],

            // Warna Netral
            [
                'category_id' => $categories->where('name', 'Warna Netral')->first()->id,
                'name' => 'Cat Hitam Pekat',
                'price' => 100000,
                'description' => 'Cat warna hitam pekat untuk berbagai keperluan',
                'status' => 'active',
                'stock' => 60,
            ],
            [
                'category_id' => $categories->where('name', 'Warna Netral')->first()->id,
                'name' => 'Cat Putih Murni',
                'price' => 100000,
                'description' => 'Cat warna putih murni dengan daya tutup tinggi',
                'status' => 'active',
                'stock' => 55,
            ],

            // Warna Pastel
            [
                'category_id' => $categories->where('name', 'Warna Pastel')->first()->id,
                'name' => 'Cat Pink Pastel',
                'price' => 110000,
                'description' => 'Cat warna pink pastel lembut untuk nuansa soft',
                'status' => 'active',
                'stock' => 8,
            ],
            [
                'category_id' => $categories->where('name', 'Warna Pastel')->first()->id,
                'name' => 'Cat Biru Muda Pastel',
                'price' => 110000,
                'description' => 'Cat warna biru muda pastel yang menenangkan',
                'status' => 'active',
                'stock' => 5,
            ],

            // Additional products - 1 per category
            // Warna Primer - Product 4
            [
                'category_id' => $categories->where('name', 'Warna Primer')->first()->id,
                'name' => 'Cat Magenta Primary',
                'price' => 160000,
                'description' => 'Cat warna magenta primer untuk mixing warna yang lebih kompleks',
                'status' => 'active',
                'stock' => 35,
            ],

            // Warna Sekunder - Product 4
            [
                'category_id' => $categories->where('name', 'Warna Sekunder')->first()->id,
                'name' => 'Cat Tosca Secondary',
                'price' => 130000,
                'description' => 'Cat warna tosca hasil campuran biru dan hijau dengan sedikit putih',
                'status' => 'active',
                'stock' => 28,
            ],

            // Warna Tersier - Product 3
            [
                'category_id' => $categories->where('name', 'Warna Tersier')->first()->id,
                'name' => 'Cat Biru Ungu Tertiary',
                'price' => 135000,
                'description' => 'Warna tersier kombinasi biru dan ungu untuk efek misterius',
                'status' => 'active',
                'stock' => 18,
            ],

            // Warna Netral - Product 3
            [
                'category_id' => $categories->where('name', 'Warna Netral')->first()->id,
                'name' => 'Cat Abu-abu Medium',
                'price' => 105000,
                'description' => 'Cat warna abu-abu medium untuk shading dan mixing',
                'status' => 'active',
                'stock' => 42,
            ],

            // Warna Pastel - Product 3
            [
                'category_id' => $categories->where('name', 'Warna Pastel')->first()->id,
                'name' => 'Cat Lavender Pastel',
                'price' => 115000,
                'description' => 'Cat warna lavender pastel untuk suasana dreamy dan romantic',
                'status' => 'active',
                'stock' => 12,
            ],
        ];

        foreach ($products as $productData) {
            $product = Product::create($productData);

            // Create product image for each product
            $this->createProductImage($product);
        }

        $this->command->info('Products seeded successfully with images!');
    }

    private function createProductImage(Product $product): void
    {
        // Create image directory if not exists
        $imageDir = storage_path('app/public/product-images');
        if (!is_dir($imageDir)) {
            mkdir($imageDir, 0755, true);
        }

        // Download image from picsum.photos with seed based on product ID
        $imageName = 'product-' . $product->id . '-' . time() . '.jpg';
        $imagePath = 'product-images/' . $imageName;

        // Use different seed for each product to get different images
        $seed = $product->id * 777; // Different multiplier for variety
        $url = "https://picsum.photos/seed/{$seed}/800/800";

        try {
            $imageContent = @file_get_contents($url);

            if ($imageContent) {
                $fullPath = storage_path('app/public/' . $imagePath);
                file_put_contents($fullPath, $imageContent);

                // Create product image record
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imagePath,
                    'is_primary' => true,
                ]);

                $this->command->info("Image created for: {$product->name}");
            }
        } catch (\Exception $e) {
            $this->command->warn("Failed to download image for {$product->name}: " . $e->getMessage());
        }
    }
}