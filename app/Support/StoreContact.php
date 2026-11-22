<?php

namespace App\Support;

/**
 * Kontak toko (WhatsApp / Instagram) dari config('lumeria.contact'), memakai pembersih
 * ContactLinks yang sudah ada. Mengembalikan null bila belum diisi atau tidak valid,
 * sehingga view cukup menyembunyikan tombolnya.
 */
final class StoreContact
{
    /** Link wa.me toko, opsional dengan teks pesan awal. */
    public static function whatsapp(?string $message = null): ?string
    {
        return ContactLinks::whatsapp(
            config('lumeria.contact.whatsapp'),
            (string) config('lumeria.contact.country_code', '62'),
            $message,
        );
    }

    /** ['handle' => ..., 'url' => ...] atau null. */
    public static function instagram(): ?array
    {
        return ContactLinks::instagram(config('lumeria.contact.instagram'));
    }
}
