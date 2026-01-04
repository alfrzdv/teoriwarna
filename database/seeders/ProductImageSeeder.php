<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure the product-images directory exists
        if (!Storage::disk('public')->exists('product-images')) {
            Storage::disk('public')->makeDirectory('product-images');
        }

        $products = Product::all();

        // Sample product image URLs from Unsplash (art supplies related)
        $sampleImages = [
            'https://images.unsplash.com/photo-1513364776144-60967b0f800f?w=800&h=800&fit=crop', // Paint brushes
            'https://images.unsplash.com/photo-1579762715459-5a068c289fda?w=800&h=800&fit=crop', // Watercolor paints
            'https://images.unsplash.com/photo-1596548438137-d51ea5c83ca5?w=800&h=800&fit=crop', // Colored pencils
            'https://images.unsplash.com/photo-1612198188060-c7c2a3b66eae?w=800&h=800&fit=crop', // Art palette
            'https://images.unsplash.com/photo-1604002789433-1fd1ed7299aa?w=800&h=800&fit=crop', // Sketchbook
            'https://images.unsplash.com/photo-1615799998603-7c6270a45196?w=800&h=800&fit=crop', // Markers
            'https://images.unsplash.com/photo-1605116955542-0c5fe8c7b0c1?w=800&h=800&fit=crop', // Canvas
            'https://images.unsplash.com/photo-1618944847828-82e943c3bdb7?w=800&h=800&fit=crop', // Paint tubes
        ];

        foreach ($products as $index => $product) {
            // Clear existing images for this product
            $product->product_images()->delete();

            // Get random image URLs for this product
            $imageCount = rand(2, 4);
            $shuffledImages = $sampleImages;
            shuffle($shuffledImages);

            for ($i = 0; $i < $imageCount; $i++) {
                $isPrimary = ($i === 0);

                try {
                    // Generate unique filename
                    $filename = Str::ulid() . '.jpg';
                    $imagePath = 'product-images/' . $filename;

                    // Download image from URL
                    $imageUrl = $shuffledImages[$i % count($shuffledImages)];
                    $imageContent = @file_get_contents($imageUrl);

                    if ($imageContent !== false) {
                        // Save to storage
                        Storage::disk('public')->put($imagePath, $imageContent);

                        // Create database record
                        ProductImage::create([
                            'product_id' => $product->id,
                            'image_path' => $imagePath,
                            'is_primary' => $isPrimary,
                        ]);

                        $this->command->info("Added image for product: {$product->name}");
                    } else {
                        $this->command->warn("Failed to download image for: {$product->name}");
                    }
                } catch (\Exception $e) {
                    $this->command->error("Error adding image for {$product->name}: {$e->getMessage()}");
                }
            }
        }

        $this->command->info('Product images seeded successfully!');
    }
}
