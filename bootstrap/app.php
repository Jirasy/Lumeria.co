<?php

use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\ForcePublicUrl;
use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Situs berjalan di belakang proxy (preview lokal, hosting, atau CDN).
        // Tanpa ini, asset() dan redirect memakai alamat internal proxy, bukan host publik.
        $middleware->trustProxies(at: '*');

        $middleware->web(append: [
            ForcePublicUrl::class,
            SetLocale::class,
        ]);

        $middleware->alias([
            'admin' => EnsureUserIsAdmin::class,
        ]);

        $middleware->encryptCookies(except: [
            'locale',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
