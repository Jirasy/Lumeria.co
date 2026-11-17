<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('category', 30)->index();   // bola_susu | lumer_lainnya
            $table->string('name', 100);
            $table->string('slug', 120)->unique();
            $table->text('description_id');
            $table->text('description_en');
            $table->unsignedInteger('price');          // Rupiah, tanpa desimal
            $table->boolean('is_signature')->default(false);
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
