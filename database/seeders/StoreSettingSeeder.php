<?php

namespace Database\Seeders;

use App\Models\StoreSetting;
use Illuminate\Database\Seeder;

class StoreSettingSeeder extends Seeder
{
    public function run(): void
    {
        StoreSetting::create([
            'store_name' => 'Teori Warna Store',
            'address' => 'Jl. Soekarno Hatta No. 123, Bandung, Jawa Barat 40123',
            'email' => 'info@teoriwarna.com',
            'phone' => '022-1234567',
        ]);

        $this->command->info('Store settings seeded successfully!');
    }
}