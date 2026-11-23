{{--
    Layout halaman autentikasi (masuk, daftar, lupa kata sandi, dan sejenisnya).
    Memakai aset yang sama dengan situs publik: Bootstrap lokal + token tema di public/css/lumeria.css
    dan komponen di public/css/lumeria-foodtech.css. Tidak memuat Vite, Tailwind, atau font dari luar.

    Slot opsional: <x-slot:title>Judul tab browser</x-slot:title>
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
<body>
    <a href="#main" class="skip-link">{{ __('common.skip') }}</a>

    {{-- Pembungkus memotong dekorasi latar agar halaman tidak melebar di layar kecil --}}
    <div class="auth-page d-flex flex-column min-vh-100">
        <header class="container py-3">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                <a href="{{ route('home') }}" class="btn btn-sm btn-outline-dark d-inline-flex align-items-center gap-1">
                    <x-icon name="chevron-left" :size="14" />{{ __('auth.back_home') }}
                </a>
                <x-language-switcher bootstrap />
            </div>
        </header>

        <main id="main" class="flex-grow-1 d-flex align-items-center py-4" tabindex="-1">
            <div class="container">
                <div class="row justify-content-center align-items-center g-4 g-xl-5">

                    {{-- Identitas merek: ringkas di layar kecil, lengkap dengan ilustrasi di layar besar --}}
                    <div class="col-12 col-lg-5 text-center text-lg-start">
                        <a href="{{ route('home') }}" class="auth-brand d-inline-flex align-items-center gap-3 text-decoration-none">
                            <img src="{{ asset('images/lumeria-logo.png') }}" alt="" width="64" height="64" class="rounded-circle" style="object-fit: cover;">
                            <span class="auth-brand-name fw-bold text-lumeria">{{ __('common.brand') }}</span>
                        </a>
                        <p class="lead mt-3 mb-0 d-none d-sm-block">{{ __('home.hero_identity') }}</p>
                        <p class="fst-italic text-lumeria mt-2 mb-0 d-none d-sm-block">&ldquo;{{ __('home.tagline') }}&rdquo;</p>

                        <div class="hero-art mt-4 d-none d-lg-block ms-0" aria-hidden="true">
                            <div class="hero-blob"></div>
                            <x-product-art slug="original-milk" :bare="true" :size="170" class="hero-ball hero-ball-1" />
                            <x-product-art slug="bola-teh" :bare="true" :size="130" class="hero-ball hero-ball-2" />
                            <x-product-art slug="bola-kopi" :bare="true" :size="150" class="hero-ball hero-ball-3" />
                        </div>
                    </div>

                    {{-- Kartu autentikasi --}}
                    <div class="col-12 col-md-10 col-lg-6 col-xl-5">
                        <div class="card auth-card">
                            <div class="card-body p-4 p-md-5">
                                {{ $slot }}
                            </div>
                        </div>

                        <p class="text-center small mt-4 mb-0">
                            <a href="{{ route('products') }}">{{ __('auth.browse_products') }}</a>
                        </p>
                    </div>

                </div>
            </div>
        </main>

        <footer class="container py-3 text-center small text-muted">
            &copy; {{ date('Y') }} {{ __('common.brand') }}. {{ __('common.footer_rights') }}
        </footer>
    </div>
</body>
</html>
