<?php

namespace Database\Seeders;

use App\Models\OrderType;
use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class OrderTypeAndPaymentSeeder extends Seeder
{
    public function run(): void
    {
        // Order Types
        OrderType::create(['name' => 'Dine-in', 'code' => 'DI']);
        OrderType::create(['name' => 'Take-away', 'code' => 'TA']);
        OrderType::create(['name' => 'Delivery', 'code' => 'DE']);

        // Payment Methods
        PaymentMethod::create(['name' => 'Cash', 'code' => 'CASH', 'active' => true]);
        PaymentMethod::create(['name' => 'QRIS', 'code' => 'QRIS', 'active' => true]);
        PaymentMethod::create(['name' => 'Debit Card', 'code' => 'DEBIT', 'active' => true]);
        PaymentMethod::create(['name' => 'Credit Card', 'code' => 'CC', 'active' => true]);
        PaymentMethod::create(['name' => 'E-Wallet', 'code' => 'EWALLET', 'active' => true]);
    }
}
