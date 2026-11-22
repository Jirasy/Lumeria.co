{{--
    Layout area akun (dashboard dan profil). Memakai navbar dan footer yang sama dengan situs publik,
    Bootstrap lokal, dan token tema di public/css/lumeria.css. Tidak memuat Vite, Tailwind, Alpine, atau font luar.

    Slot opsional:
        <x-slot:title>Judul tab browser</x-slot:title>
        <x-slot:header>Judul dan subjudul halaman</x-slot:header>
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
    @stack('styles')
</head>
<body class="d-flex flex-column min-vh-100">
    <a href="#main" class="skip-link">{{ __('common.skip') }}</a>

    @include('partials.navbar')

    <main id="main" class="flex-grow-1 py-4 py-md-5" tabindex="-1">
        <div class="container">
            @isset($header)
                <header class="mb-4">
                    {{ $header }}
                </header>
            @endisset

            {{ $slot }}
        </div>
    </main>

    @include('partials.footer')

    <script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
    @stack('scripts')
</body>
</html>
