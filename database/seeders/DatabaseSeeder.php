<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@brewly.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create cashier user
        User::create([
            'name' => 'Cashier',
            'email' => 'cashier@brewly.com',
            'password' => Hash::make('password'),
            'role' => 'cashier',
        ]);

        // Call other seeders
        $this->call([
            OrderTypeAndPaymentSeeder::class,
        ]);
    }
}

