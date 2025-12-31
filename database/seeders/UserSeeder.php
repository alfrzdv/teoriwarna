<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@teoriwarna.com',
            'password' => Hash::make('password'),
            'phone' => '081234567890',
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Regular Users
        for ($i = 1; $i <= 5; $i++) {
            $user = User::create([
                'name' => "User {$i}",
                'email' => "user{$i}@example.com",
                'password' => Hash::make('password'),
                'phone' => '0812345678' . str_pad($i + 10, 2, '0', STR_PAD_LEFT),
                'role' => 'user',
                'is_active' => true,
            ]);

            // Create user address
            UserAddress::create([
                'user_id' => $user->id,
                'recipient_name' => "User {$i}",
                'phone' => '0812345678' . str_pad($i + 10, 2, '0', STR_PAD_LEFT),
                'address' => "Jl. Contoh No. {$i}, RT.00{$i}/RW.00{$i}",
                'city' => 'Bandung',
                'province' => 'Jawa Barat',
                'postal_code' => '40' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'is_default' => true,
            ]);
        }

        $this->command->info('Users seeded successfully!');
    }
}