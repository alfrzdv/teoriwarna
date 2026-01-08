<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class AdditionalProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();

        $productsData = [
            // Seni Digital & Desain (ID: 7) - already has 3, need 1 more
            [
                'category_name' => 'Seni Digital & Desain',
                'products' => [
                    [
                        'name' => 'Stylus Pen Premium',
                        'price' => 450000,
                        'stock' => 12,
                        'description' => 'Stylus pen dengan pressure sensitivity untuk digital art',
                    ],
                ],
            ],

            // Lukisan & Warna (ID: 8) - already has 3, need 1 more
            [
                'category_name' => 'Lukisan & Warna',
                'products' => [
                    [
                        'name' => 'Set Cat Minyak 12 Warna',
                        'price' => 320000,
                        'stock' => 8,
                        'description' => 'Set cat minyak profesional dengan 12 warna pilihan',
                    ],
                ],
            ],

            // Patung & Ukiran (ID: 9) - already has 3, need 1 more
            [
                'category_name' => 'Patung & Ukiran',
                'products' => [
                    [
                        'name' => 'Pahat Kayu Set 6 Pcs',
                        'price' => 275000,
                        'stock' => 10,
                        'description' => 'Set pahat kayu berkualitas untuk mengukir detail halus',
                    ],
                ],
            ],

            // Kaligrafi & Seni Tulis (ID: 10) - already has 3, need 1 more
            [
                'category_name' => 'Kaligrafi & Seni Tulis',
                'products' => [
                    [
                        'name' => 'Tinta Kaligrafi Hitam Premium',
                        'price' => 95000,
                        'stock' => 20,
                        'description' => 'Tinta kaligrafi hitam pekat, tahan lama dan tidak luntur',
                    ],
                ],
            ],

            // Kerajinan Tangan & Jahitan (ID: 11) - already has 3, need 1 more
            [
                'category_name' => 'Kerajinan Tangan & Jahitan',
                'products' => [
                    [
                        'name' => 'Benang Sulam Warna Warni 100 Pcs',
                        'price' => 180000,
                        'stock' => 15,
                        'description' => 'Benang sulam berkualitas dengan 100 pilihan warna',
                    ],
                ],
            ],

            // Gambar & Arsir (ID: 12) - already has 3, need 1 more
            [
                'category_name' => 'Gambar & Arsir',
                'products' => [
                    [
                        'name' => 'Pensil Sketsa Profesional Set',
                        'price' => 165000,
                        'stock' => 18,
                        'description' => 'Set pensil sketsa dari 2H sampai 8B untuk berbagai teknik',
                    ],
                ],
            ],

            // Kertas & Origami (ID: 13) - already has 3, need 1 more
            [
                'category_name' => 'Kertas & Origami',
                'products' => [
                    [
                        'name' => 'Kertas Origami Metalik 100 Lembar',
                        'price' => 75000,
                        'stock' => 25,
                        'description' => 'Kertas origami dengan efek metalik, berbagai warna',
                    ],
                ],
            ],

            // Seni Digital Interaktif (ID: 14) - already has 3, need 1 more
            [
                'category_name' => 'Seni Digital Interaktif',
                'products' => [
                    [
                        'name' => 'LED Strip RGB 5 Meter',
                        'price' => 385000,
                        'stock' => 9,
                        'description' => 'LED strip RGB untuk instalasi seni digital interaktif',
                    ],
                ],
            ],

            // Seni Rupa & Dekorasi (ID: 15) - already has 3, need 1 more
            [
                'category_name' => 'Seni Rupa & Dekorasi',
                'products' => [
                    [
                        'name' => 'Frame Foto Kayu Premium',
                        'price' => 145000,
                        'stock' => 14,
                        'description' => 'Frame foto kayu berkualitas untuk memajang karya seni',
                    ],
                ],
            ],
        ];

        foreach ($productsData as $categoryData) {
            $category = $categories->where('name', $categoryData['category_name'])->first();

            if (!$category) {
                continue;
            }

            foreach ($categoryData['products'] as $productData) {
                $product = Product::create([
                    'category_id' => $category->id,
                    'name' => $productData['name'],
                    'price' => $productData['price'],
                    'stock' => $productData['stock'],
                    'description' => $productData['description'],
                    'status' => 'active',
                ]);

                // Create product image with placeholder
                $this->createProductImage($product, $category->name);
            }
        }
    }

    private function createProductImage(Product $product, string $categoryName): void
    {
        // Create image directory if not exists
        $imageDir = storage_path('app/public/product-images');
        if (!is_dir($imageDir)) {
            mkdir($imageDir, 0755, true);
        }

        // Download image from picsum.photos (better quality than placeholder.com)
        $imageName = 'product-' . $product->id . '-' . time() . '.jpg';
        $imagePath = 'product-images/' . $imageName;

        // Use different seed for each product to get different images
        $seed = $product->id * 100;
        $url = "https://picsum.photos/seed/{$seed}/800/800";

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
        }
    }
}
