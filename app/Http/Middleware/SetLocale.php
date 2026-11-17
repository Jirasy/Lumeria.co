<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public const SUPPORTED = ['id', 'en'];
    public const DEFAULT   = 'id';

    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->cookie('locale', self::DEFAULT);

        if (! in_array($locale, self::SUPPORTED, true)) {
            $locale = self::DEFAULT;
        }

        App::setLocale($locale);
        Carbon::setLocale($locale);

        return $next($request);
    }
}