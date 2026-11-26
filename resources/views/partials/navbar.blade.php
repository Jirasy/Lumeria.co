@inject('cart', 'App\Services\Cart')
@php
    // Tiga perspektif: tamu, pelanggan login, admin.
    $user = auth()->user();

    $links = [
        ['home', 'nav.home'],
        ['products', 'nav.products'],
        ['order', 'nav.order'],
    ];

    if ($user) {
        $links[] = ['orders.index', 'foodtech.nav.my_orders'];
    }

    // Menu admin dikelompokkan dalam satu dropdown agar bilah navigasi tidak penuh.
    // Format: [rute, label, pola rute aktif]
    $adminMenu = $user?->isAdmin() ? [
        ['admin.dashboard',      'foodtech.nav.admin', 'admin.dashboard'],
        ['admin.products.index', 'products_admin.nav', 'admin.products.*'],
        ['admin.users.index',    'users.nav',          'admin.users.*'],
        ['team',                 'nav.team',           'team'],
        ['islamic',              'nav.islamic',        'islamic'],
        ['economic',             'nav.economic',       'economic'],
    ] : [];
    $adminActive = request()->routeIs('admin.*', 'team', 'islamic', 'economic');
    $locale      = app()->getLocale();
    $cartCount   = $cart->count();
@endphp

<div class="announce">
    <div class="shell announce-inner">
        <span>{{ __('home.tagline') }}</span>
        <a href="{{ route('products') }}">{{ __('home.cta_products') }}</a>
    </div>
</div>

<header class="site-header">
    <nav class="navbar navbar-expand-xl site-nav" aria-label="{{ __('nav.menu') }}">
        <div class="shell nav-inner">
            <a class="navbar-brand brand" href="{{ route('home') }}" aria-label="{{ __('common.brand') }}">
                <span class="brand-mark">
                    <img src="{{ asset('images/logo-icon.png') }}" alt="" width="42" height="42">
                </span>
                <span class="brand-name">{{ __('common.brand') }}</span>
            </a>

            <div class="nav-quick">
                <a href="{{ route('order') }}" class="icon-btn" aria-label="{{ __('nav.cart') }}">
                    <x-icon name="bag" :size="18" />
                    @if ($cartCount > 0)
                        <span class="badge rounded-pill text-bg-danger">{{ $cartCount }}</span>
                    @endif
                </a>
            </div>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="{{ __('nav.menu') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav nav-main">
                    @foreach ($links as [$route, $label])
                        @php $active = request()->routeIs($route, $route . '.*'); @endphp
                        <li class="nav-item">
                            <a class="nav-link{{ $active ? ' active' : '' }}" href="{{ route($route) }}"@if ($active) aria-current="page"@endif>
                                {{ __($label) }}
                            </a>
                        </li>
                    @endforeach

                    @if ($adminMenu)
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle{{ $adminActive ? ' active' : '' }}" href="#" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false">{{ __('users.admin_menu') }}</a>
                            <ul class="dropdown-menu">
                                @foreach ($adminMenu as [$route, $label, $pattern])
                                    <li><a class="dropdown-item{{ request()->routeIs($pattern) ? ' active' : '' }}" href="{{ route($route) }}">{{ __($label) }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                    @endif
                </ul>

                <div class="nav-actions">
                    <div class="lang-switch" role="group" aria-label="{{ __('common.language') }}">
                        @foreach (['id' => 'ID', 'en' => 'EN'] as $code => $text)
                            <a href="{{ route('lang.switch', $code) }}"
                               class="btn{{ $locale === $code ? ' active' : '' }}"@if ($locale === $code) aria-current="true"@endif>{{ $text }}</a>
                        @endforeach
                    </div>

                    <a href="{{ route('order') }}" class="icon-btn d-none d-xl-inline-grid" aria-label="{{ __('nav.cart') }}">
                        <x-icon name="bag" :size="18" />
                        @if ($cartCount > 0)
                            <span class="badge rounded-pill text-bg-danger">{{ $cartCount }}</span>
                        @endif
                    </a>

                    @auth
                        <a href="{{ route('profile.edit') }}" class="nav-avatar d-inline-flex align-items-center gap-2 text-decoration-none"
                           aria-label="{{ __('profile.open_profile') }}: {{ $user->name }}"@if (request()->routeIs('profile.edit')) aria-current="page"@endif>
                            <x-user-avatar :user="$user" :size="36" />
                            <span class="d-xl-none fw-semibold">{{ $user->name }}</span>
                        </a>
                        <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-dark">{{ __('nav.dashboard') }}</a>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-link text-dark text-decoration-none">{{ __('nav.logout') }}</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-sm btn-outline-dark">{{ __('nav.login') }}</a>
                        <a href="{{ route('register') }}" class="btn btn-sm btn-lumeria">{{ __('nav.register') }}</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
</header>
