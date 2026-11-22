<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // customer | admin. Akun lama otomatis menjadi customer.
            $table->string('role', 20)->default('customer')->after('password');
        });

        Schema::table('products', function (Blueprint $table) {
            // promo | discount | best_seller | new (null = tanpa badge promosi)
            $table->string('badge', 30)->nullable()->after('is_signature');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('badge');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
