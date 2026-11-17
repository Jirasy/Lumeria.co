<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('customer_name', 100);
            $table->string('customer_phone', 20);
            $table->text('customer_address')->nullable();

            $table->unsignedInteger('subtotal');
            $table->string('payment_method', 20)->default('cash'); // cash | paylater

            // Khusus Paylater (null kalau bayar tunai)
            $table->unsignedTinyInteger('tenor_months')->nullable();
            $table->decimal('margin_rate', 5, 2)->nullable();      // % per bulan, flat
            $table->unsignedInteger('margin_total')->default(0);
            $table->unsignedInteger('installment_amount')->nullable();

            $table->unsignedInteger('grand_total');
            $table->string('status', 20)->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};