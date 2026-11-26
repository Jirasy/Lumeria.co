<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Empat akun admin tim Lumeria.co. Nama lengkap diambil dari config('lumeria.team'),
 * email berbentuk <key>@<LUMERIA_ADMIN_EMAIL_DOMAIN> (bawaan: lumeria.test).
 *
 * Kata sandi:
 *  - LUMERIA_ADMIN_PASSWORD terisi : dipakai untuk keempat akun (dan diatur ulang setiap seeder dijalankan).
 *  - kosong                        : akun baru mendapat kata sandi acak yang ditampilkan SEKALI di terminal;
 *                                    akun yang sudah ada tidak diubah kata sandinya.
 * Tidak ada kata sandi bawaan di kode, karena repositori ini publik.
 *
 * Aman dijalankan berulang kali: akun dicari lewat email, tidak digandakan.
 */
class AdminSeeder extends Seeder
{
    /** Key anggota tim (lihat config/lumeria.php) yang menjadi admin. */
    public const ADMIN_KEYS = ['sanaya', 'aliful', 'jimny', 'alfatih'];

    public function run(): void
    {
        $domain   = config('lumeria.admin.email_domain') ?: 'lumeria.test';
        $shared   = config('lumeria.admin.password');
        $team     = collect(config('lumeria.team'));
        $generated = [];

        foreach (self::ADMIN_KEYS as $key) {
            $email = "{$key}@{$domain}";
            $user  = User::firstOrNew(['email' => $email]);
            $isNew = ! $user->exists;

            $attributes = [
                'name'              => $team->firstWhere('key', $key)['name'] ?? Str::headline($key),
                'role'              => User::ROLE_ADMIN,
                'email_verified_at' => $user->email_verified_at ?? now(),
            ];

            if ($shared) {
                $attributes['password'] = $shared;
            } elseif ($isNew) {
                $attributes['password'] = $generated[$email] = Str::password(16, symbols: false);
            }

            // Peran tidak mass-assignable, jadi diisi eksplisit di seeder.
            $user->forceFill($attributes)->save();
        }

        if ($generated !== []) {
            $this->command?->warn('Kata sandi acak untuk admin baru (catat sekarang, tidak ditampilkan lagi):');
            $this->command?->table(
                ['Email', 'Kata sandi'],
                collect($generated)->map(fn ($password, $email) => [$email, $password])->values()->all(),
            );
        }
    }
}
