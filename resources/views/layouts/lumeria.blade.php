<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | {{ __('common.brand') }}</title>

    <link href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet">

    <style>
        :root {
            --bs-body-bg: #fff8f0;
            --bs-body-color: #3b2a20;
            --bs-body-font-family: "Segoe UI", system-ui, -apple-system, "Helvetica Neue", Arial, sans-serif;
            --lumeria: #a35a1f;
            --lumeria-dark: #874a17;
        }
        html { scroll-behavior: smooth; }
        main:focus { outline: none; }
        [id^="product-"] { scroll-margin-top: 5.5rem; }

        h1, h2, .display-4, .display-6 { font-family: Georgia, "Times New Roman", serif; letter-spacing: -.01em; }
        .display-4 { font-size: clamp(2.25rem, 7vw, 3.5rem); }
        .lead { font-size: clamp(1.05rem, 2.6vw, 1.25rem); }

        :focus-visible { outline: 3px solid rgba(163, 90, 31, .55); outline-offset: 2px; }
        .skip-link {
            position: absolute; left: .75rem; top: -4rem; z-index: 2000;
            padding: .5rem 1rem; border-radius: .5rem; background: #fff; color: var(--lumeria-dark);
            box-shadow: 0 .25rem .75rem rgba(0, 0, 0, .2); transition: top .15s ease;
        }
        .skip-link:focus { top: .75rem; }

        .text-lumeria { color: var(--lumeria); }
        .bg-lumeria { background: var(--lumeria); color: #fff; }
        .btn-lumeria {
            --bs-btn-color: #fff;
            --bs-btn-bg: var(--lumeria);
            --bs-btn-border-color: var(--lumeria);
            --bs-btn-hover-color: #fff;
            --bs-btn-hover-bg: var(--lumeria-dark);
            --bs-btn-hover-border-color: var(--lumeria-dark);
            --bs-btn-active-color: #fff;
            --bs-btn-active-bg: #733f13;
            --bs-btn-active-border-color: #733f13;
            --bs-btn-border-radius: 50rem;
        }
        .card { --bs-card-bg: #fff; --bs-card-border-radius: 1rem; }

        .navbar.bg-white { --bs-bg-opacity: .92; backdrop-filter: blur(8px); }
        .nav-actions { border-top: 1px solid var(--bs-border-color); padding: .75rem 0 .5rem; margin-top: .25rem; }
        @media (min-width: 1200px) {
            .navbar .nav-link { padding-inline: .55rem; font-size: .95rem; }
            .nav-actions { border-top: 0; padding: 0; margin-top: 0; }
        }
        .lang-switch .btn { min-width: 2.75rem; }

        @media (pointer: coarse) {
            .btn, .form-control, .form-select { min-height: 2.75rem; }
            .nav-link { padding-block: .65rem; }
            .form-check-input { width: 1.4rem; height: 1.4rem; }
        }

        .hero-section {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #fde7d3 0%, #fff8f0 65%);
        }
        .hero-section::before {
            content: "";
            position: absolute;
            width: 28rem; height: 28rem;
            right: -8rem; top: -10rem;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(163, 90, 31, .14), transparent 70%);
        }
        .hero-section > .container { position: relative; }

        .hero-art { position: relative; width: min(22rem, 100%); aspect-ratio: 11 / 10; margin-inline: auto; }
        .hero-blob {
            position: absolute; inset: 7%;
            border-radius: 50%;
            background: radial-gradient(circle at 30% 30%, #ffe3c2, #f6c48d 70%);
            opacity: .7;
        }
        .hero-ball { position: absolute; height: auto; aspect-ratio: 1 / 1; filter: drop-shadow(0 12px 14px rgba(90, 50, 20, .25)); }
        .hero-ball-1 { left: 9%; top: 15%; width: 48%; }
        .hero-ball-2 { right: 7%; top: 5%; width: 37%; }
        .hero-ball-3 { left: 30%; bottom: 3%; width: 43%; }
        @media (max-width: 575.98px) { .hero-art { width: min(16rem, 100%); } }

        .product-card { transition: transform .18s ease, box-shadow .18s ease; }
        .product-card:hover { transform: translateY(-4px); box-shadow: 0 .75rem 1.5rem rgba(90, 50, 20, .14) !important; }
        .product-art {
            position: relative;
            display: flex; align-items: center; justify-content: center;
            height: 9.5rem;
            overflow: hidden;
            background: radial-gradient(circle at 30% 25%, #fff 0, var(--art-bg) 60%);
        }
        .product-art::before, .product-art::after {
            content: ""; position: absolute; border-radius: 50%; background: rgba(255, 255, 255, .55);
        }
        .product-art::before { width: 7rem; height: 7rem; left: -2rem; top: -2.5rem; }
        .product-art::after { width: 4rem; height: 4rem; right: -1rem; bottom: -1.5rem; }
        .product-art svg, .product-art img { position: relative; z-index: 1; }
        .product-art svg { filter: drop-shadow(0 6px 8px rgba(90, 50, 20, .18)); transition: transform .18s ease; }
        .product-card:hover .product-art svg { transform: translateY(-3px) scale(1.05); }

        .cart-fab {
            position: fixed; right: 1rem; bottom: calc(1rem + env(safe-area-inset-bottom, 0px)); z-index: 1030;
            display: inline-flex; align-items: center; gap: .5rem; padding: .7rem 1.1rem;
        }
        @media (max-width: 1199.98px) { .has-fab main { padding-bottom: 4.5rem; } }

        @media (max-width: 575.98px) {
            .stack-table thead {
                position: absolute; width: 1px; height: 1px; overflow: hidden; clip: rect(0 0 0 0); white-space: nowrap;
            }
            .stack-table, .stack-table tbody, .stack-table tfoot,
            .stack-table tr, .stack-table td, .stack-table th { display: block; width: 100%; }
            .stack-table tbody tr {
                position: relative; margin-bottom: .75rem; padding: .5rem .85rem;
                border: 1px solid var(--bs-border-color); border-radius: .85rem; background: #fff;
            }
            .stack-table td {
                display: flex; justify-content: space-between; align-items: center; gap: 1rem;
                padding: .4rem 0; border: 0; text-align: right; background: transparent;
            }
            .stack-table td::before { content: attr(data-label); font-weight: 600; text-align: left; color: var(--bs-secondary-color); }
            .stack-table td:not([data-label])::before, .stack-table td[data-label=""]::before { content: none; }
            .stack-table td[data-label=""] { justify-content: flex-end; }
            .stack-table td form.d-flex { flex: 0 1 11rem; }
            .stack-table tfoot tr { display: flex; justify-content: space-between; padding: .25rem .85rem; }
            .stack-table tfoot th { display: block; width: auto; border: 0; padding: .25rem 0; background: transparent; }
            .stack-table tfoot th:empty { display: none; }
            .stack-selectable tbody tr { padding-left: 2.6rem; }
            .stack-selectable td.stack-radio { position: absolute; left: .85rem; top: .9rem; width: auto; padding: 0; display: block; }
        }

        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }
            .product-card, .product-art svg, .skip-link { transition: none; }
            .product-card:hover, .product-card:hover .product-art svg { transform: none; }
        }
    </style>
    @stack('styles')
</head>
@php
    $cartCount = app(\App\Services\Cart::class)->count();
    $showFab   = $cartCount > 0 && ! request()->routeIs('order', 'order.*');
@endphp
<body class="d-flex flex-column min-vh-100{{ $showFab ? ' has-fab' : '' }}">
    <a href="#main" class="skip-link">{{ __('common.skip') }}</a>

    @include('partials.navbar')

    <main id="main" class="flex-grow-1" tabindex="-1">
        @if (session('error') || $errors->any())
            <div class="container pt-3">
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        @endif

        @yield('content')
    </main>

    @if (session('status'))
        <div class="toast-container position-fixed top-0 start-50 translate-middle-x p-3 mt-5" style="z-index: 1090">
            <div id="statusToast" class="toast align-items-center text-bg-success border-0" role="status" aria-live="polite" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ session('status') }}
                        @if (session('cart_link'))
                            <a href="{{ route('order') }}" class="text-white fw-semibold ms-1">{{ __('order.view_cart') }}</a>
                        @endif
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>
        @push('scripts')
            <script>
                (function () {
                    var el = document.getElementById('statusToast');
                    if (el && window.bootstrap) { new bootstrap.Toast(el, { delay: 7000 }).show(); }
                })();
            </script>
        @endpush
    @endif

    @if ($showFab)
        <a href="{{ route('order') }}" class="cart-fab btn btn-lumeria shadow d-xl-none">
            {{ __('nav.cart') }} <span class="badge text-bg-light">{{ $cartCount }}</span>
        </a>
    @endif

    <footer class="bg-white border-top py-4 mt-5">
        <div class="container text-center text-muted small">
            &copy; {{ date('Y') }} {{ __('common.brand') }}. {{ __('common.footer_rights') }}
        </div>
    </footer>

    <script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
    @stack('scripts')
</body>
</html>