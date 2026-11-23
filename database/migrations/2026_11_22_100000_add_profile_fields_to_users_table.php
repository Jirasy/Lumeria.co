<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Kolom profil opsional: nomor telepon, jenis kelamin, tanggal lahir, dan foto profil.
     * Foto disimpan di disk privat (storage/app/private/avatars) dan disajikan lewat rute avatar.show.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 20)->nullable()->after('email');
            $table->string('gender', 10)->nullable()->after('phone');   // male | female | other
            $table->date('birth_date')->nullable()->after('gender');
            $table->string('avatar_path')->nullable()->after('birth_date');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'gender', 'birth_date', 'avatar_path']);
        });
    }
};
