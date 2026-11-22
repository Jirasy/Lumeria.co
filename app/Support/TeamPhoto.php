<?php

namespace App\Support;

/**
 * Mencari foto anggota tim di folder public/.
 *
 * Aturan pencarian:
 *   1. Jika path yang diisi di config('lumeria.team.*.photo') ada isinya,
 *      hanya path tersebut yang dicek; jika tidak ada filenya, kembalikan null.
 *   2. Jika photo kosong/null, cari public/images/team/<key>.webp | .jpg | .jpeg | .png.
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

        $hasConfigured = $configured !== null && trim($configured) !== '';

        if ($hasConfigured) {
            $relative = self::normalizeRelativePath((string) $configured);

            if ($relative === null) {
                return null;
            }

            return is_file($publicPath . '/' . $relative) ? $relative : null;
        }

        $slug = preg_replace('/[^A-Za-z0-9_-]/', '', $key) ?? '';
        if ($slug === '') {
            return null;
        }

        foreach (self::EXTENSIONS as $ext) {
            $relative = self::normalizeRelativePath(self::DIR . '/' . $slug . '.' . $ext);

            if ($relative !== null && is_file($publicPath . '/' . $relative)) {
                return $relative;
            }
        }

        return null;
    }

    private static function normalizeRelativePath(string $path): ?string
    {
        $relative = ltrim(str_replace('\\', '/', trim($path)), '/');

        if ($relative === ''
            || str_contains($relative, '..')
            || ! preg_match('~\.(webp|jpe?g|png)$~i', $relative)) {
            return null;
        }

        return $relative;
    }
}