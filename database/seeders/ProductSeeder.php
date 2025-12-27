<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

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
            ],
            [
                'category_id' => $categories->where('name', 'Warna Primer')->first()->id,
                'name' => 'Cat Kuning Primary',
                'price' => 150000,
                'description' => 'Cat warna kuning primer cerah untuk hasil optimal',
                'status' => 'active',
            ],
            [
                'category_id' => $categories->where('name', 'Warna Primer')->first()->id,
                'name' => 'Cat Biru Primary',
                'price' => 150000,
                'description' => 'Cat warna biru primer dengan pigmen berkualitas',
                'status' => 'active',
            ],

            // Warna Sekunder
            [
                'category_id' => $categories->where('name', 'Warna Sekunder')->first()->id,
                'name' => 'Cat Hijau Secondary',
                'price' => 125000,
                'description' => 'Cat warna hijau hasil campuran kuning dan biru',
                'status' => 'active',
            ],
            [
                'category_id' => $categories->where('name', 'Warna Sekunder')->first()->id,
                'name' => 'Cat Oranye Secondary',
                'price' => 125000,
                'description' => 'Cat warna oranye hasil campuran merah dan kuning',
                'status' => 'active',
            ],
            [
                'category_id' => $categories->where('name', 'Warna Sekunder')->first()->id,
                'name' => 'Cat Ungu Secondary',
                'price' => 125000,
                'description' => 'Cat warna ungu hasil campuran merah dan biru',
                'status' => 'active',
            ],

            // Warna Tersier
            [
                'category_id' => $categories->where('name', 'Warna Tersier')->first()->id,
                'name' => 'Cat Merah Oranye Tertiary',
                'price' => 135000,
                'description' => 'Warna tersier kombinasi merah dan oranye',
                'status' => 'active',
            ],
            [
                'category_id' => $categories->where('name', 'Warna Tersier')->first()->id,
                'name' => 'Cat Kuning Hijau Tertiary',
                'price' => 135000,
                'description' => 'Warna tersier kombinasi kuning dan hijau',
                'status' => 'active',
            ],

            // Warna Netral
            [
                'category_id' => $categories->where('name', 'Warna Netral')->first()->id,
                'name' => 'Cat Hitam Pekat',
                'price' => 100000,
                'description' => 'Cat warna hitam pekat untuk berbagai keperluan',
                'status' => 'active',
            ],
            [
                'category_id' => $categories->where('name', 'Warna Netral')->first()->id,
                'name' => 'Cat Putih Murni',
                'price' => 100000,
                'description' => 'Cat warna putih murni dengan daya tutup tinggi',
                'status' => 'active',
            ],

            // Warna Pastel
            [
                'category_id' => $categories->where('name', 'Warna Pastel')->first()->id,
                'name' => 'Cat Pink Pastel',
                'price' => 110000,
                'description' => 'Cat warna pink pastel lembut untuk nuansa soft',
                'status' => 'active',
            ],
            [
                'category_id' => $categories->where('name', 'Warna Pastel')->first()->id,
                'name' => 'Cat Biru Muda Pastel',
                'price' => 110000,
                'description' => 'Cat warna biru muda pastel yang menenangkan',
                'status' => 'active',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        $this->command->info('Products seeded successfully!');
    }
}