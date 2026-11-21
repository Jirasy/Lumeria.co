<?php

namespace App\Support;

/**
 * Pembersih tautan kontak untuk halaman Our Team.
 *
 * Setiap method mengembalikan null bila input kosong atau tidak valid, sehingga
 * view cukup menyembunyikan tombolnya. Hasilnya tidak pernah berupa tautan rusak
 * atau skema berbahaya (javascript:, data:, dsb.).
 */
final class ContactLinks
{
    /** Segmen URL Instagram yang bukan nama pengguna. */
    private const INSTAGRAM_RESERVED = ['p', 'reel', 'reels', 'tv', 'stories', 'explore', 'accounts', 'direct'];

    /**
     * Nomor WhatsApp -> https://wa.me/<digit>[?text=...]
     *
     * Menerima: 0812-3456-7890, +62 812 3456 7890, 6281234567890, 81234567890,
     * atau link wa.me / api.whatsapp.com?phone=... yang ditempel langsung.
     * Nomor asing sebaiknya ditulis dengan awalan +kode negara.
     */
    public static function whatsapp(?string $input, string $countryCode = '62', ?string $message = null): ?string
    {
        $raw = trim((string) $input);
        if ($raw === '') {
            return null;
        }

        if (preg_match('~(?:wa\.me/|[?&]phone=)\+?(\d+)~i', $raw, $m)) {
            $raw = '+' . $m[1];
        }

        $hasPlus = str_starts_with($raw, '+');
        $digits  = preg_replace('/\D+/', '', $raw) ?? '';
        if ($digits === '') {
            return null;
        }

        if ($hasPlus) {
            // sudah format internasional
        } elseif (str_starts_with($digits, '00')) {
            $digits = substr($digits, 2);
        } elseif (str_starts_with($digits, '0')) {
            $digits = $countryCode . substr($digits, 1);
        } elseif ($countryCode === '62' && str_starts_with($digits, '8')) {
            $digits = '62' . $digits;
        }
        // selain itu dianggap sudah memuat kode negara (mis. 6281...)

        // E.164: 8-15 digit, tidak diawali 0
        if (! preg_match('/^[1-9]\d{7,14}$/', $digits)) {
            return null;
        }

        $url = 'https://wa.me/' . $digits;

        if ($message !== null && trim($message) !== '') {
            $url .= '?text=' . rawurlencode($message);
        }

        return $url;
    }

    /**
     * @username / username / URL profil -> ['handle' => 'username', 'url' => 'https://www.instagram.com/username/']
     */
    public static function instagram(?string $input): ?array
    {
        $v = trim((string) $input);
        if ($v === '') {
            return null;
        }

        if (preg_match('~^(?:https?://)?(?:www\.|m\.)?instagram\.com/([^/?#]+)~i', $v, $m)) {
            $v = $m[1];
        }

        $v = ltrim($v, '@');

        if (! preg_match('/^[A-Za-z0-9._]{1,30}$/', $v)
            || in_array(strtolower($v), self::INSTAGRAM_RESERVED, true)) {
            return null;
        }

        return ['handle' => $v, 'url' => 'https://www.instagram.com/' . $v . '/'];
    }

    /**
     * Tautan portofolio / situs. Hanya http(s); tanpa skema otomatis menjadi https.
     */
    public static function url(?string $input): ?string
    {
        $v = trim((string) $input);
        if ($v === '' || strlen($v) > 2048) {
            return null;
        }

        if (! str_contains($v, '://')) {
            // javascript:, mailto:, data:, dll. ditolak; "example.com:8080" tetap dianggap domain.
            if (preg_match('~^[a-z][a-z0-9+.\-]*:(?!\d)~i', $v)) {
                return null;
            }

            $v = 'https://' . ltrim($v, '/');
        }

        if (! filter_var($v, FILTER_VALIDATE_URL)) {
            return null;
        }

        $parts = parse_url($v);
        if ($parts === false) {
            return null;
        }

        $scheme = strtolower($parts['scheme'] ?? '');
        $host   = $parts['host'] ?? '';

        if (! in_array($scheme, ['http', 'https'], true)
            || $host === ''
            || ! str_contains($host, '.')
            || isset($parts['user'])
            || isset($parts['pass'])) {
            return null;
        }

        return $v;
    }
}
