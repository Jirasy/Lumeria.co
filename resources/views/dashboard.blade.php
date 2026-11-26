@php
    $user  = Auth::user();
    $cards = [
        ['orders.index', __('foodtech.nav.my_orders'), __('account.card_orders')],
        ['order',        __('nav.order'),              __('account.card_order')],
        ['products',     __('nav.products'),           __('account.card_products')],
        ['profile.edit', __('profile.nav_profile'),    __('account.card_profile')],
    ];

    if ($user->isAdmin()) {
        $cards[] = ['admin.dashboard', __('foodtech.nav.admin'), __('account.card_admin')];
    }
@endphp
<x-app-layout>
    <x-slot:title>{{ __('Dashboard') }}</x-slot:title>

    <x-slot:header>
        <h1 class="h2 fw-bold mb-1">{{ __('account.greeting', ['name' => $user->name]) }}</h1>
        <p class="text-muted mb-0">{{ __('account.subtitle') }}</p>
    </x-slot:header>

    <div class="row g-3 g-lg-4">
        @foreach ($cards as [$route, $title, $text])
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card account-card h-100">
                    <div class="card-body p-4">
                        <h2 class="h5 fw-bold mb-2">
                            <a href="{{ route($route) }}" class="stretched-link text-decoration-none text-lumeria">{{ $title }}</a>
                        </h2>
                        <p class="text-muted mb-0">{{ $text }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>