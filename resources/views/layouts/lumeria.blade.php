<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#F7F4EE">
    <title>@yield('title') | {{ __('common.brand') }}</title>
    <meta name="description" content="@yield('description', __('home.tagline') . ' - ' . __('common.brand'))">

    <link rel="preload" href="{{ asset('fonts/fraunces-var.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{ asset('fonts/inter-var.woff2') }}" as="font" type="font/woff2" crossorigin>

    <link href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    {{-- Palet warna dan gaya situs: edit token di public/css/lumeria.css (bagian 1). --}}
    <link href="{{ asset('css/lumeria.css') }}?v={{ is_file(public_path('css/lumeria.css')) ? filemtime(public_path('css/lumeria.css')) : 1 }}" rel="stylesheet">
    {{-- Komponen etalase foodtech: kartu produk, carousel, catering, detail, footer. --}}
    <link href="{{ asset('css/lumeria-foodtech.css') }}?v={{ is_file(public_path('css/lumeria-foodtech.css')) ? filemtime(public_path('css/lumeria-foodtech.css')) : 1 }}" rel="stylesheet">
    <link href="{{ asset('css/lumeria-redesign.css') }}?v={{ is_file(public_path('css/lumeria-redesign.css')) ? filemtime(public_path('css/lumeria-redesign.css')) : 1 }}" rel="stylesheet">
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
            <div class="shell pt-3">
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
        <a href="{{ route('order') }}" class="cart-fab btn btn-lumeria shadow">
            {{ __('nav.cart') }} <span class="badge text-bg-light">{{ $cartCount }}</span>
        </a>
    @endif

    @include('partials.footer')

    <script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script>
        (function () {
            var header = document.querySelector('.site-header');
            if (!header) { return; }
            var onScroll = function () {
                header.classList.toggle('is-stuck', window.scrollY > 8);
            };
            onScroll();
            window.addEventListener('scroll', onScroll, { passive: true });
        })();
    </script>
    @stack('scripts')
</body>
</html>
