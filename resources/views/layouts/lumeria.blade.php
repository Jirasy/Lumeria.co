<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | {{ __('common.brand') }}</title>

    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">


    <style>
        :root {
    --bs-body-bg: #fff8f0;
    --bs-body-color: #3b2a20;
    --bs-body-font-family: "Segoe UI", system-ui, -apple-system, "Helvetica Neue", Arial, sans-serif;
    --lumeria: #a35a1f;
    --lumeria-dark: #874a17;
    }
    h1, h2, .display-4, .display-6 { font-family: Georgia, "Times New Roman", serif; letter-spacing: -.01em; }

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
    .navbar.bg-white { --bs-bg-opacity: .88; backdrop-filter: blur(8px); }
    .card { --bs-card-bg: #fff; --bs-card-border-radius: 1rem; }

    .hero-section {
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, #fde7d3 0%, #fff8f0 65%);
    }
    .hero-section::before {
    content: "";
    position: absolute;
    width: 28rem;
    height: 28rem;
    right: -8rem;
    top: -10rem;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(163, 90, 31, .14), transparent 70%);
    }
    .hero-section > .container { position: relative; }

    .hero-art { position: relative; width: 22rem; max-width: 100%; height: 20rem; }
    .hero-blob {
    position: absolute;
    inset: 1.5rem;
    border-radius: 50%;
    background: radial-gradient(circle at 30% 30%, #ffe3c2, #f6c48d 70%);
    opacity: .7;
    }
    .hero-ball { position: absolute; filter: drop-shadow(0 12px 14px rgba(90, 50, 20, .25)); }
    .hero-ball-1 { left: 2rem; top: 3rem; }
    .hero-ball-2 { right: 1.5rem; top: 1rem; }
    .hero-ball-3 { left: 6.5rem; bottom: .5rem; }

    .product-card { transition: transform .18s ease, box-shadow .18s ease; }
    .product-card:hover { transform: translateY(-4px); box-shadow: 0 .75rem 1.5rem rgba(90, 50, 20, .14) !important; }
    .product-art {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 9.5rem;
    overflow: hidden;
    background: radial-gradient(circle at 30% 25%, #fff 0, var(--art-bg) 60%);
    }
    .product-art::before, .product-art::after {
    content: "";
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, .55);
    }
    .product-art::before { width: 7rem; height: 7rem; left: -2rem; top: -2.5rem; }
    .product-art::after { width: 4rem; height: 4rem; right: -1rem; bottom: -1.5rem; }
    .product-art svg, .product-art img { position: relative; z-index: 1; }
    .product-art svg { filter: drop-shadow(0 6px 8px rgba(90, 50, 20, .18)); transition: transform .18s ease; }
    .product-card:hover .product-art svg { transform: translateY(-3px) scale(1.05); }

    @media (prefers-reduced-motion: reduce) {
    .product-card, .product-art svg { transition: none; }
    .product-card:hover, .product-card:hover .product-art svg { transform: none; }
    }
    </style>
    @stack('styles')
</head>
<body class="d-flex flex-column min-vh-100">
    @include('partials.navbar')

    <main id="main" class="flex-grow-1">
        @if (session('status') || session('error') || $errors->any())
            <div class="container pt-3">
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
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

    <footer class="bg-white border-top py-4 mt-5">
        <div class="container text-center text-muted small">
            &copy; {{ date('Y') }} {{ __('common.brand') }}. {{ __('common.footer_rights') }}
        </div>
    </footer>

    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    @stack('scripts')
</body>
</html>