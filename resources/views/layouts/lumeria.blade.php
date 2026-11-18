<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | {{ __('common.brand') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --bs-body-bg: #fff8f0;
            --bs-body-color: #3b2a20;
            --lumeria: #a35a1f;
            --lumeria-dark: #874a17;
        }
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
        }
        .card { --bs-card-bg: #fff; }
        .hero-section { background: linear-gradient(135deg, #fde7d3 0%, #fff8f0 70%); }
        .hero-emoji { font-size: 5rem; letter-spacing: .5rem; }
        .product-emoji { font-size: 3rem; line-height: 1; }
        .product-card { transition: transform .15s ease, box-shadow .15s ease; }
        .product-card:hover { transform: translateY(-3px); }
        @media (prefers-reduced-motion: reduce) {
            .product-card { transition: none; }
            .product-card:hover { transform: none; }
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>