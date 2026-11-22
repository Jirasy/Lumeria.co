<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class TeamController extends Controller
{
    public function __invoke(): View
    {
        $rawMembers = config('lumeria.team', []);

        $members = collect($rawMembers)
            ->map(function (array $member): array {
                $photoUrl = $this->resolvePhotoUrl($member['photo'] ?? null);

                return array_merge($member, [
                    'photo_url' => $photoUrl,
                ]);
            })
            ->values()
            ->all();

        return view('team', [
            'members' => $members,
        ]);
    }

    private function resolvePhotoUrl(?string $photo): ?string
    {
        if (! is_string($photo) || trim($photo) === '') {
            return null;
        }

        $relativePath = ltrim(str_replace('\\', '/', trim($photo)), '/');

        // Tolak path traversal
        if (str_contains($relativePath, '..')) {
            return null;
        }

        $publicRoot = realpath(public_path());
        $fullPath   = realpath(public_path($relativePath));

        if ($publicRoot === false || $fullPath === false) {
            return null;
        }

        // Pastikan file berada di dalam public/ dan benar-benar berupa file
        $normalizedRoot = rtrim($publicRoot, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        if (! str_starts_with($fullPath, $normalizedRoot) || ! is_file($fullPath)) {
            return null;
        }

        $version = filemtime($fullPath) ?: time();

        return asset($relativePath) . '?v=' . $version;
    }
}