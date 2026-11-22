<?php

namespace App\Support;

/**
 * Mencari foto anggota tim di folder public/.
 *
 * Urutan pencarian:
 *   1. path yang diisi di config('lumeria.team.*.photo') (relatif terhadap public/)
 *   2. public/images/team/<key>.webp | .jpg | .jpeg | .png
 *
 * Mengembalikan path relatif (mis. "images/team/sanaya.jpg") hanya jika filenya
 * benar-benar ada; selain itu null, supaya view memakai placeholder inisial dan
 * tidak pernah menampilkan gambar rusak.
 */
final class TeamPhoto
{
    public const DIR = 'images/team';

    public const EXTENSIONS = ['webp', 'jpg', 'jpeg', 'png'];

    public static function resolve(string $key, ?string $configured = null, ?string $publicPath = null): ?string
    {
        $publicPath = rtrim($publicPath ?? public_path(), '/\\');
        $candidates = [];

        if ($configured !== null && trim($configured) !== '') {
            $candidates[] = trim($configured);
        }

        $slug = preg_replace('/[^A-Za-z0-9_-]/', '', $key) ?? '';
        if ($slug !== '') {
            foreach (self::EXTENSIONS as $ext) {
                $candidates[] = self::DIR . '/' . $slug . '.' . $ext;
            }
        }

        foreach ($candidates as $relative) {
            $relative = ltrim(str_replace('\\', '/', $relative), '/');

            if (str_contains($relative, '..')
                || ! preg_match('~\.(webp|jpe?g|png)$~i', $relative)) {
                continue;
            }

            if (is_file($publicPath . '/' . $relative)) {
                return $relative;
            }
        }

        return null;
    }
}
