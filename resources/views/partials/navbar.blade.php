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
        ['admin.dashboard',   'foodtech.nav.admin', 'admin.dashboard'],
        ['admin.products.index', 'products_admin.nav', 'admin.products.*'],
        ['admin.users.index', 'users.nav',          'admin.users.*'],
        ['team',              'nav.team',           'team'],
        ['islamic',           'nav.islamic',        'islamic'],
        ['economic',          'nav.economic',       'economic'],
    ] : [];
    $adminActive = request()->routeIs('admin.*', 'team', 'islamic', 'economic');
    $locale = app()->getLocale();
@endphp
<nav class="navbar navbar-expand-xl bg-white border-bottom sticky-top" aria-label="{{ __('nav.menu') }}">
    <div class="container-fluid px-3 px-xl-4">
        <a class="navbar-brand d-inline-flex align-items-center gap-2 fw-bold text-lumeria"
        href="{{ route('home') }}"
        aria-label="{{ __('common.brand') }}">


    <img
        src="{{ asset('images/lumeria-logo.png') }}"
        alt=""
        width="48"
        height="48"
        class="rounded-circle"
        style="object-fit: cover;"
    >


    <span>{{ __('common.brand') }}</span>
    </a>


        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="{{ __('nav.menu') }}">
            <span class="navbar-toggler-icon"></span>
        </button>


        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto mb-2 mb-xl-0">
                @foreach ($links as [$route, $label])
                    @php $active = request()->routeIs($route, $route . '.*'); @endphp
                    <li class="nav-item">
                        <a class="nav-link{{ $active ? ' active fw-semibold' : '' }}" href="{{ route($route) }}"@if ($active) aria-current="page"@endif>
                            {{ __($label) }}
                            @if ($route === 'order' && $cart->count() > 0)<span class="badge rounded-pill text-bg-danger">{{ $cart->count() }}</span>@endif
                        </a>
                    </li>
                @endforeach

                @if ($adminMenu)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle{{ $adminActive ? ' active fw-semibold' : '' }}" href="#" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">{{ __('users.admin_menu') }}</a>
                        <ul class="dropdown-menu">
                            @foreach ($adminMenu as [$route, $label, $pattern])
                                <li><a class="dropdown-item{{ request()->routeIs($pattern) ? ' active' : '' }}" href="{{ route($route) }}">{{ __($label) }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                @endif
            </ul>


            <div class="nav-actions d-flex flex-wrap align-items-center gap-2">
                <div class="btn-group btn-group-sm lang-switch" role="group" aria-label="{{ __('common.language') }}">
                    @foreach (['id' => 'ID', 'en' => 'EN'] as $code => $text)
                        <a href="{{ route('lang.switch', $code) }}"
                           class="btn btn-outline-dark{{ $locale === $code ? ' active' : '' }}"@if ($locale === $code) aria-current="true"@endif>{{ $text }}</a>
                    @endforeach
                </div>


                @auth
                    <a href="{{ route('profile.edit') }}" class="nav-avatar d-inline-flex align-items-center gap-2 text-decoration-none"
                       aria-label="{{ __('profile.open_profile') }}: {{ $user->name }}"@if (request()->routeIs('profile.edit')) aria-current="page"@endif>
                        <x-user-avatar :user="$user" :size="36" />
                        <span class="d-xl-none fw-semibold text-dark">{{ $user->name }}</span>
                    </a>
                    <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-dark">{{ __('nav.dashboard') }}</a>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-link text-dark">{{ __('nav.logout') }}</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-sm btn-outline-dark">{{ __('nav.login') }}</a>
                    <a href="{{ route('register') }}" class="btn btn-sm btn-lumeria">{{ __('nav.register') }}</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
