<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Menu Variants (Size, Sugar Level, Ice Level, dll)
        Schema::create('menu_variants', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // 'Size', 'Sugar Level', 'Ice Level'
            $table->string('type'); // 'size', 'sugar', 'ice', 'topping'
            $table->timestamps();
        });

        // Menu Variant Options
        Schema::create('variant_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_variant_id')->constrained('menu_variants')->onDelete('cascade');
            $table->string('name'); // 'Small', 'Medium', 'Large' atau '0%', '50%', '100%'
            $table->decimal('price_modifier', 10, 2)->default(0); // Additional price
            $table->timestamps();
        });

        // Menu Variant Assignment
        Schema::create('product_menu_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('menu_variant_id')->constrained('menu_variants')->onDelete('cascade');
            $table->boolean('required')->default(false);
            $table->timestamps();
        });

        // Order Types (Dine-in, Take-away, Delivery)
        Schema::create('order_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // 'Dine-in', 'Take-away', 'Delivery'
            $table->string('code'); // 'DI', 'TA', 'DE'
            $table->timestamps();
        });

        // Payment Methods
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // 'Cash', 'QRIS', 'Debit', 'Credit', 'E-wallet'
            $table->string('code'); // 'CASH', 'QRIS', 'DEBIT', 'CC', 'EWALLET'
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // Customer Info
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->integer('points')->default(0);
            $table->timestamps();
        });

        // Shift Management
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->dateTime('opened_at');
            $table->dateTime('closed_at')->nullable();
            $table->decimal('opening_balance', 10, 2);
            $table->decimal('closing_balance', 10, 2)->nullable();
            $table->decimal('expected_total', 10, 2)->nullable();
            $table->decimal('difference', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->timestamps();
        });

        // User Activity Log
        Schema::create('user_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('action'); // 'login', 'void_item', 'discount_applied', 'refund', etc
            $table->text('description')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });

        // Update Transactions table
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('order_type_id')->nullable()->constrained('order_types')->onDelete('set null');
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('set null');
            $table->foreignId('shift_id')->nullable()->constrained('shifts')->onDelete('restrict');
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('service_charge', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->boolean('paid')->default(false);
            $table->boolean('void')->default(false);
            $table->text('void_reason')->nullable();
        });

        // Transaction Payment Details (untuk multi-payment)
        Schema::create('transaction_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade');
            $table->foreignId('payment_method_id')->constrained('payment_methods')->onDelete('restrict');
            $table->decimal('amount', 10, 2);
            $table->string('reference')->nullable(); // untuk QRIS, transfer, dll
            $table->timestamps();
        });

        // Transaction Item Variants
        Schema::create('transaction_detail_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_detail_id')->constrained('transaction_details')->onDelete('cascade');
            $table->foreignId('variant_option_id')->constrained('variant_options')->onDelete('restrict');
            $table->decimal('price_modifier', 10, 2)->default(0);
            $table->timestamps();
        });

        // Hold / Pending Orders
        Schema::create('hold_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shift_id')->constrained('shifts')->onDelete('cascade');
            $table->string('order_name');
            $table->json('items'); // Simpan item as JSON
            $table->decimal('total', 10, 2);
            $table->dateTime('held_at');
            $table->string('status')->default('hold'); // 'hold', 'recalled', 'expired'
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hold_orders');
        Schema::dropIfExists('transaction_detail_variants');
        Schema::dropIfExists('transaction_payments');
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('order_type_id');
            $table->dropConstrainedForeignId('customer_id');
            $table->dropConstrainedForeignId('shift_id');
        });
        Schema::dropIfExists('user_logs');
        Schema::dropIfExists('shifts');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('payment_methods');
        Schema::dropIfExists('order_types');
        Schema::dropIfExists('product_menu_variants');
        Schema::dropIfExists('variant_options');
        Schema::dropIfExists('menu_variants');
    }
};
