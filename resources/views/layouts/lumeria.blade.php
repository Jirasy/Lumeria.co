<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | {{ __('common.brand') }}</title>


    <link href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    {{-- Palet warna dan gaya situs: edit token di public/css/lumeria.css (bagian 1). --}}
    <link href="{{ asset('css/lumeria.css') }}?v={{ is_file(public_path('css/lumeria.css')) ? filemtime(public_path('css/lumeria.css')) : 1 }}" rel="stylesheet">
    {{-- Komponen etalase foodtech: badge pojok, carousel, kartu catering, footer gelap. --}}
    <link href="{{ asset('css/lumeria-foodtech.css') }}?v={{ is_file(public_path('css/lumeria-foodtech.css')) ? filemtime(public_path('css/lumeria-foodtech.css')) : 1 }}" rel="stylesheet">
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


    @include('partials.footer')


    <script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
    @stack('scripts')
</body>
</html>
