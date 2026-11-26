<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

/**
 * Memakai satu URL publik tetap untuk semua tautan dan aset.
 *
 * Berguna saat aplikasi berjalan di belakang proxy yang menulis ulang header Host
 * (misalnya preview port atau reverse proxy internal): tanpa ini, asset() dan route()
 * menghasilkan alamat internal yang tidak bisa dibuka browser.
 *
 * Tidak berpengaruh bila LUMERIA_PUBLIC_URL kosong, sehingga perilaku bawaan Laravel
 * (mengikuti host permintaan) tetap dipakai di produksi.
 */
class ForcePublicUrl
{
    public function handle(Request $request, Closure $next): Response
    {
        $publicUrl = config('lumeria.public_url');

        if (is_string($publicUrl) && $publicUrl !== '') {
            URL::forceRootUrl(rtrim($publicUrl, '/'));

            if ($scheme = parse_url($publicUrl, PHP_URL_SCHEME)) {
                URL::forceScheme($scheme);
            }
        }

        return $next($request);
    }
}
