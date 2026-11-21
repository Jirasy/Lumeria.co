<?php

namespace App\Support;

final class TeamPhoto
{
    public const DIR = 'images/team';

    public const EXTENSIONS = ['webp', 'jpg', 'jpeg', 'png'];

    public static function resolve(string $key, ?string $configured = null, ?string $publicPath = null): ?string
    {
        $publicPath = rtrim($publicPath ?? public_path(), '/\\');
        $candidates = [];

        $hasExplicitPhoto = $configured !== null && trim($configured) !== '';

        if ($hasExplicitPhoto) {
            $candidates[] = trim($configured);
        } else {
            $slug = preg_replace('/[^A-Za-z0-9_-]/', '', $key) ?? '';
            if ($slug !== '') {
                foreach (self::EXTENSIONS as $ext) {
                    $candidates[] = self::DIR . '/' . $slug . '.' . $ext;
                }
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