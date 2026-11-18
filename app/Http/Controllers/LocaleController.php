<?php

namespace App\Http\Controllers;

use App\Http\Middleware\SetLocale;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cookie;

class LocaleController extends Controller
{
    public function switch(string $locale): RedirectResponse
    {
        abort_unless(in_array($locale, SetLocale::SUPPORTED, true), 404);

        // 1 tahun, supaya tetap ada setelah logout
        Cookie::queue('locale', $locale, 60 * 24 * 365);

        return back(fallback: url('/'));
    }
}