<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Warna Primer',
                'description' => 'Warna-warna dasar yang tidak dapat dicampur dari warna lain: Merah, Kuning, Biru',
                'is_active' => true,
            ],
            [
                'name' => 'Warna Sekunder',
                'description' => 'Warna yang dihasilkan dari pencampuran dua warna primer: Hijau, Oranye, Ungu',
                'is_active' => true,
            ],
            [
                'name' => 'Warna Tersier',
                'description' => 'Warna yang dihasilkan dari pencampuran warna primer dengan warna sekunder',
                'is_active' => true,
            ],
            [
                'name' => 'Warna Netral',
                'description' => 'Warna netral seperti Hitam, Putih, Abu-abu',
                'is_active' => true,
            ],
            [
                'name' => 'Warna Pastel',
                'description' => 'Warna-warna lembut dengan tingkat saturasi rendah',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        $this->command->info('Categories seeded successfully!');
    }
}