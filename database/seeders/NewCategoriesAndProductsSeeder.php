<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class NewCategoriesAndProductsSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        Product::query()->delete();
        Category::query()->delete();

        // Categories and their products
        $categoriesData = [
            [
                'name' => 'Seni Digital & Desain',
                'description' => 'Peralatan untuk desain digital dan seni digital',
                'products' => [
                    ['name' => 'Tablet Grafis', 'price' => 2500000, 'stock' => 15],
                    ['name' => 'Stylus Pen', 'price' => 500000, 'stock' => 30],
                    ['name' => 'Software Design (Adobe Photoshop, Illustrator)', 'price' => 3000000, 'stock' => 50],
                    ['name' => 'Laptop untuk Desain', 'price' => 15000000, 'stock' => 10],
                    ['name' => 'Monitor Kalibrasi Warna', 'price' => 5000000, 'stock' => 8],
                    ['name' => 'Mouse Desain', 'price' => 750000, 'stock' => 25],
                    ['name' => 'Kartu Grafis (GPU)', 'price' => 8000000, 'stock' => 12],
                    ['name' => 'Alat Pengeditan Video (Adobe Premiere, Final Cut Pro)', 'price' => 3500000, 'stock' => 40],
                    ['name' => 'Drawing Pad', 'price' => 1500000, 'stock' => 20],
                    ['name' => 'Graphic Pen', 'price' => 400000, 'stock' => 35],
                ]
            ],
            [
                'name' => 'Lukisan & Warna',
                'description' => 'Perlengkapan untuk melukis dan mewarnai',
                'products' => [
                    ['name' => 'Kuas Lukis', 'price' => 150000, 'stock' => 50],
                    ['name' => 'Cat Minyak', 'price' => 350000, 'stock' => 40],
                    ['name' => 'Cat Akr

ilik', 'price' => 250000, 'stock' => 45],
                    ['name' => 'Kanvas', 'price' => 200000, 'stock' => 60],
                    ['name' => 'Palet Warna', 'price' => 100000, 'stock' => 55],
                    ['name' => 'Palet Air', 'price' => 120000, 'stock' => 48],
                    ['name' => 'Cat Air', 'price' => 180000, 'stock' => 52],
                    ['name' => 'Pensil Warna', 'price' => 220000, 'stock' => 65],
                    ['name' => 'Cat Poster', 'price' => 160000, 'stock' => 38],
                ]
            ],
            [
                'name' => 'Patung & Ukiran',
                'description' => 'Alat untuk membuat patung dan ukiran',
                'products' => [
                    ['name' => 'Pahat Kayu', 'price' => 300000, 'stock' => 25],
                    ['name' => 'Alat Ukir Batu', 'price' => 450000, 'stock' => 18],
                    ['name' => 'Alat Ukir Kayu', 'price' => 350000, 'stock' => 22],
                    ['name' => 'Gergaji Tangan', 'price' => 280000, 'stock' => 30],
                    ['name' => 'Alat Ukir Logam', 'price' => 520000, 'stock' => 15],
                    ['name' => 'Pahat Batu', 'price' => 380000, 'stock' => 20],
                    ['name' => 'Alat Pengasah Pahat', 'price' => 240000, 'stock' => 28],
                    ['name' => 'Pemotong Batu', 'price' => 620000, 'stock' => 12],
                    ['name' => 'Mesin Pemotong Ukir', 'price' => 1800000, 'stock' => 8],
                ]
            ],
            [
                'name' => 'Kaligrafi & Seni Tulis',
                'description' => 'Perlengkapan untuk kaligrafi dan seni menulis',
                'products' => [
                    ['name' => 'Kuas Kaligrafi', 'price' => 180000, 'stock' => 35],
                    ['name' => 'Tinta Cina', 'price' => 140000, 'stock' => 42],
                    ['name' => 'Kertas Kaligrafi', 'price' => 90000, 'stock' => 58],
                    ['name' => 'Pembaris Kertas', 'price' => 75000, 'stock' => 45],
                    ['name' => 'Pensil Kaligrafi', 'price' => 110000, 'stock' => 40],
                    ['name' => 'Tinta Emas', 'price' => 280000, 'stock' => 28],
                    ['name' => 'Kertas Jepang', 'price' => 120000, 'stock' => 50],
                    ['name' => 'Sablon Tinta', 'price' => 160000, 'stock' => 32],
                    ['name' => 'Pembuat Stempel Kaligrafi', 'price' => 350000, 'stock' => 15],
                ]
            ],
            [
                'name' => 'Kerajinan Tangan & Jahitan',
                'description' => 'Peralatan untuk kerajinan tangan dan menjahit',
                'products' => [
                    ['name' => 'Mesin Jahit', 'price' => 2800000, 'stock' => 12],
                    ['name' => 'Benang Warna', 'price' => 85000, 'stock' => 70],
                    ['name' => 'Jarum', 'price' => 45000, 'stock' => 80],
                    ['name' => 'Gunting Kain', 'price' => 150000, 'stock' => 55],
                    ['name' => 'Alat Anyaman', 'price' => 220000, 'stock' => 35],
                    ['name' => 'Kain Perca', 'price' => 95000, 'stock' => 65],
                    ['name' => 'Alat Bordir', 'price' => 380000, 'stock' => 28],
                    ['name' => 'Renda', 'price' => 120000, 'stock' => 48],
                    ['name' => 'Gunting Kertas', 'price' => 95000, 'stock' => 60],
                    ['name' => 'Mesin Overlock', 'price' => 3200000, 'stock' => 9],
                    ['name' => 'Paku Jahit', 'price' => 65000, 'stock' => 75],
                ]
            ],
            [
                'name' => 'Gambar & Arsir',
                'description' => 'Alat menggambar dan membuat arsiran',
                'products' => [
                    ['name' => 'Pensil Charcoal', 'price' => 110000, 'stock' => 45],
                    ['name' => 'Spidol Permanen', 'price' => 95000, 'stock' => 60],
                    ['name' => 'Kertas Arsir', 'price' => 85000, 'stock' => 55],
                    ['name' => 'Penghapus Khusus', 'price' => 65000, 'stock' => 70],
                    ['name' => 'Pensil Arsir', 'price' => 130000, 'stock' => 50],
                    ['name' => 'Pen Penyorot', 'price' => 75000, 'stock' => 62],
                    ['name' => 'Kertas Gambar', 'price' => 105000, 'stock' => 58],
                    ['name' => 'Spidol Tekstur', 'price' => 140000, 'stock' => 42],
                    ['name' => 'Gambar Sketsa', 'price' => 180000, 'stock' => 35],
                ]
            ],
            [
                'name' => 'Kertas & Origami',
                'description' => 'Berbagai jenis kertas untuk origami dan kerajinan',
                'products' => [
                    ['name' => 'Kertas Origami', 'price' => 75000, 'stock' => 80],
                    ['name' => 'Kertas Tebal', 'price' => 95000, 'stock' => 65],
                    ['name' => 'Lem Kertas', 'price' => 55000, 'stock' => 75],
                    ['name' => 'Gunting Kertas', 'price' => 85000, 'stock' => 70],
                    ['name' => 'Kertas Polos', 'price' => 68000, 'stock' => 85],
                    ['name' => 'Kertas Karton', 'price' => 110000, 'stock' => 60],
                    ['name' => 'Kertas Tisu', 'price' => 52000, 'stock' => 90],
                    ['name' => 'Kertas Transfer', 'price' => 130000, 'stock' => 48],
                    ['name' => 'Kertas Seni', 'price' => 145000, 'stock' => 42],
                ]
            ],
            [
                'name' => 'Seni Digital Interaktif',
                'description' => 'Teknologi untuk seni digital dan interaktif',
                'products' => [
                    ['name' => 'Alat VR (Virtual Reality)', 'price' => 8500000, 'stock' => 6],
                    ['name' => 'Alat AR (Augmented Reality)', 'price' => 7200000, 'stock' => 8],
                    ['name' => 'Kamera 360', 'price' => 5800000, 'stock' => 10],
                    ['name' => 'Perangkat Sensor Gerak', 'price' => 3500000, 'stock' => 12],
                    ['name' => 'Alat Pengeditan Video (DaVinci Resolve)', 'price' => 0, 'stock' => 999],
                    ['name' => 'Lensa VR', 'price' => 4200000, 'stock' => 9],
                    ['name' => 'Headset VR', 'price' => 6500000, 'stock' => 7],
                    ['name' => 'Kamera Web 4K', 'price' => 2800000, 'stock' => 15],
                ]
            ],
            [
                'name' => 'Seni Rupa & Dekorasi',
                'description' => 'Material untuk seni rupa dan dekorasi',
                'products' => [
                    ['name' => 'Stiker Dinding', 'price' => 180000, 'stock' => 45],
                    ['name' => 'Kertas Wall Art', 'price' => 220000, 'stock' => 38],
                    ['name' => 'Gambar Pemandangan', 'price' => 350000, 'stock' => 25],
                    ['name' => 'Papan Tulis', 'price' => 280000, 'stock' => 32],
                    ['name' => 'Poster Seni', 'price' => 150000, 'stock' => 50],
                    ['name' => 'Lampu LED Dekoratif', 'price' => 420000, 'stock' => 22],
                    ['name' => 'Wall Stickers', 'price' => 195000, 'stock' => 40],
                    ['name' => 'Pencetak Foto Buku Seni', 'price' => 1850000, 'stock' => 8],
                ]
            ],
        ];

        foreach ($categoriesData as $categoryData) {
            $category = Category::create([
                'name' => $categoryData['name'],
                'description' => $categoryData['description'],
            ]);

            foreach ($categoryData['products'] as $productData) {
                Product::create([
                    'category_id' => $category->id,
                    'name' => $productData['name'],
                    'description' => 'Produk berkualitas tinggi untuk kebutuhan seni dan kreativitas Anda.',
                    'price' => $productData['price'],
                    'stock' => $productData['stock'],
                    'status' => 'active',
                ]);
            }
        }

        $this->command->info('Categories and products created successfully!');
    }
}
