{{--
    Layout halaman autentikasi (masuk, daftar, lupa kata sandi, dan sejenisnya).
    Memakai aset yang sama dengan situs publik: Bootstrap lokal + token tema di public/css/lumeria.css
    dan komponen di public/css/lumeria-foodtech.css. Tidak memuat Vite, Tailwind, atau font dari luar.

    Slot opsional:
        <x-slot:title>Judul tab browser</x-slot:title>
        <x-slot:tabs>Dua tautan pil "Masuk / Daftar" di atas formulir</x-slot:tabs>
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) && trim((string) $title) !== '' ? trim((string) $title) . ' | ' : '' }}{{ __('common.brand') }}</title>

    <link href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/lumeria.css') }}?v={{ is_file(public_path('css/lumeria.css')) ? filemtime(public_path('css/lumeria.css')) : 1 }}" rel="stylesheet">
    <link href="{{ asset('css/lumeria-foodtech.css') }}?v={{ is_file(public_path('css/lumeria-foodtech.css')) ? filemtime(public_path('css/lumeria-foodtech.css')) : 1 }}" rel="stylesheet">
</head>
<body data-auth>
    <a href="#main" class="skip-link">{{ __('common.skip') }}</a>

    <div class="auth-page">
        <div class="auth-shell">

            {{-- Panel merek: pita navy di atas formulir pada layar kecil, panel penuh di layar besar --}}
            <aside class="auth-aside">
                <div class="auth-aside-top">
                    <a href="{{ route('home') }}" class="auth-home-link">
                        <x-icon name="chevron-left" :size="14" />{{ __('auth.back_home') }}
                    </a>
                    <x-language-switcher />
                </div>

                <div class="auth-aside-body">
                    <a href="{{ route('home') }}" class="auth-brand">
                        <img src="{{ asset('images/lumeria-logo.png') }}" alt="" width="56" height="56" class="auth-brand-mark">
                        <span class="auth-brand-text">
                            <span class="auth-brand-name">{{ __('common.brand') }}</span>
                            <span class="auth-brand-sub">{{ __('auth.brand_sub') }}</span>
                        </span>
                    </a>

                    <p class="auth-aside-title">{{ __('auth.aside_title') }}</p>
                    <p class="auth-aside-lead">{{ __('home.hero_identity') }}</p>

                    <ul class="auth-perks">
                        @foreach (['perk_order', 'perk_cart', 'perk_track'] as $perk)
                            <li class="auth-perk">
                                <span class="auth-perk-icon"><x-icon name="check" :size="12" /></span>
                                <span>{{ __('auth.' . $perk) }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="auth-aside-art" aria-hidden="true">
                    <x-product-art slug="original-milk" :bare="true" :size="150" class="auth-ball auth-ball-1" />
                    <x-product-art slug="bola-teh" :bare="true" :size="120" class="auth-ball auth-ball-2" />
                    <x-product-art slug="bola-kopi" :bare="true" :size="132" class="auth-ball auth-ball-3" />
                </div>

                <p class="auth-aside-note">{{ __('auth.aside_note') }}</p>
            </aside>

            <main id="main" class="auth-main" tabindex="-1">
                <div class="auth-card">
                    @isset($tabs)
                        <nav class="auth-tabs" aria-label="{{ __('auth.mode_label') }}">
                            {{ $tabs }}
                        </nav>
                    @endisset

                    {{ $slot }}
                </div>

                <p class="auth-browse mb-0">
                    <a href="{{ route('products') }}">{{ __('auth.browse_products') }}</a>
                </p>

                <p class="auth-footer mb-0">
                    &copy; {{ date('Y') }} {{ __('common.brand') }}. {{ __('common.footer_rights') }}
                </p>
            </main>

        </div>
    </div>

    {{-- Interaksi formulir tanpa dependensi: tampilkan kata sandi, kekuatan kata sandi, dan kecocokan konfirmasi. --}}
    <script>
        (function () {
            'use strict';

            var page = document.body;
            if (!page.hasAttribute('data-auth')) {
                return;
            }

            function forEach(list, callback) {
                Array.prototype.forEach.call(list, callback);
            }

            forEach(page.querySelectorAll('[data-password-toggle]'), function (button) {
                var field = document.getElementById(button.getAttribute('data-target'));
                if (!field) {
                    return;
                }

                button.addEventListener('click', function () {
                    var revealed = field.type === 'password';
                    field.type = revealed ? 'text' : 'password';
                    button.setAttribute('aria-pressed', revealed ? 'true' : 'false');

                    var label = revealed
                        ? button.getAttribute('data-hide-label')
                        : button.getAttribute('data-show-label');

                    button.setAttribute('aria-label', label);
                    button.setAttribute('title', label);
                    field.focus({ preventScroll: true });
                });
            });

            forEach(page.querySelectorAll('[data-password-strength]'), function (meter) {
                var field = document.getElementById(meter.getAttribute('data-target'));
                var output = meter.querySelector('[data-strength-text]');
                if (!field || !output) {
                    return;
                }

                var labels = (meter.getAttribute('data-labels') || '').split('|');
                var emptyLabel = meter.getAttribute('data-empty-label') || '';

                function level(value) {
                    if (!value) {
                        return 0;
                    }

                    var points = 0;
                    if (value.length >= 8) { points += 1; }
                    if (value.length >= 12) { points += 1; }
                    if (/[a-z]/.test(value) && /[A-Z]/.test(value)) { points += 1; }
                    if (/\d/.test(value) && /[^A-Za-z0-9]/.test(value)) { points += 1; }

                    return Math.min(4, Math.max(1, points + (value.length >= 8 ? 1 : 0)));
                }

                function update() {
                    var current = level(field.value);
                    meter.setAttribute('data-level', String(current));
                    output.textContent = current === 0 ? emptyLabel : (labels[current - 1] || '');
                }

                field.addEventListener('input', update);
                update();
            });

            forEach(page.querySelectorAll('[data-password-match]'), function (note) {
                var field = document.getElementById(note.getAttribute('data-target'));
                var source = document.getElementById(note.getAttribute('data-source'));
                if (!field || !source) {
                    return;
                }

                function update() {
                    var filled = field.value !== '';
                    var matched = filled && field.value === source.value;

                    note.setAttribute('data-state', !filled ? 'idle' : (matched ? 'ok' : 'bad'));
                    note.textContent = !filled ? '' : (matched
                        ? note.getAttribute('data-ok-label')
                        : note.getAttribute('data-bad-label'));
                }

                [field, source].forEach(function (element) {
                    element.addEventListener('input', update);
                });
                update();
            });
        })();
    </script>
</body>
</html>
