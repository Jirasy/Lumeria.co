{{-- Footer terang bergaya marketplace: kolom layanan, jelajahi, akun, pembayaran, dan ikuti kami. --}}
@php
    $user   = auth()->user();
    $wa     = \App\Support\StoreContact::whatsapp(__('foodtech.footer.wa_message'));
    $ig     = \App\Support\StoreContact::instagram();
    $tenors = config('lumeria.paylater.tenors', [1, 2, 3]);
    $locale = app()->getLocale();
@endphp
<footer class="ft-footer">
    <div class="container">
        <div class="row g-4 g-xl-5">
            <div class="col-6 col-md-4 col-xl">
                <h2 class="ft-footer-title">{{ __('foodtech.footer.customer_service') }}</h2>
                <ul>
                    <li><a href="{{ route('order') }}">{{ __('foodtech.footer.how_to_order') }}</a></li>
                    <li><a href="{{ $user ? route('orders.index') : route('login') }}">{{ __('foodtech.footer.track_order') }}</a></li>
                    @if ($wa)
                        <li><a href="{{ $wa }}" target="_blank" rel="noopener noreferrer">{{ __('foodtech.footer.contact_us') }}</a></li>
                    @endif
                </ul>
            </div>

            <div class="col-6 col-md-4 col-xl">
                <h2 class="ft-footer-title">{{ __('foodtech.footer.explore') }}</h2>
                <ul>
                    <li><a href="{{ route('home') }}">{{ __('nav.home') }}</a></li>
                    <li><a href="{{ route('products') }}">{{ __('nav.products') }}</a></li>
                    <li><a href="{{ route('order') }}">{{ __('nav.order') }}</a></li>
                </ul>
            </div>

            <div class="col-6 col-md-4 col-xl">
                <h2 class="ft-footer-title">{{ __('foodtech.footer.account') }}</h2>
                <ul>
                    @guest
                        <li><a href="{{ route('login') }}">{{ __('nav.login') }}</a></li>
                        <li><a href="{{ route('register') }}">{{ __('nav.register') }}</a></li>
                    @else
                        <li><a href="{{ route('profile.edit') }}">{{ __('profile.nav_profile') }}</a></li>
                        <li><a href="{{ route('orders.index') }}">{{ __('foodtech.nav.my_orders') }}</a></li>
                        <li><a href="{{ route('dashboard') }}">{{ __('nav.dashboard') }}</a></li>
                        <li><a href="{{ route('profile.edit') }}">{{ __('profile.nav_profile') }}</a></li>
                        @if ($user->isAdmin())
                            <li><a href="{{ route('admin.dashboard') }}">{{ __('foodtech.nav.admin') }}</a></li>
                            <li><a href="{{ route('admin.products.index') }}">{{ __('products_admin.nav') }}</a></li>
                            <li><a href="{{ route('admin.users.index') }}">{{ __('users.nav') }}</a></li>
                        @endif
                    @endguest
                </ul>
            </div>

            <div class="col-6 col-md-6 col-xl">
                <h2 class="ft-footer-title">{{ __('foodtech.footer.payment') }}</h2>
                <ul class="ft-pay-list">
                    <li class="ft-pay">{{ __('foodtech.footer.pay_cash') }}</li>
                    <li class="ft-pay">{{ __('foodtech.footer.pay_paylater') }}</li>
                </ul>
                <p class="ft-footer-note">{{ __('foodtech.footer.pay_note', ['min' => min($tenors), 'max' => max($tenors)]) }}</p>
            </div>

            @if ($wa || $ig)
                <div class="col-12 col-md-6 col-xl">
                    <h2 class="ft-footer-title">{{ __('foodtech.footer.follow') }}</h2>
                    <ul class="ft-follow" aria-label="{{ __('foodtech.footer.social') }}">
                        @if ($wa)
                            <li>
                                <a href="{{ $wa }}" target="_blank" rel="noopener noreferrer">
                                    <x-icon name="whatsapp" :size="20" />{{ __('foodtech.footer.whatsapp') }}
                                </a>
                            </li>
                        @endif
                        @if ($ig)
                            <li>
                                <a href="{{ $ig['url'] }}" target="_blank" rel="noopener noreferrer">
                                    <x-icon name="instagram" :size="20" />{{ __('foodtech.footer.instagram') }}
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            @endif
        </div>

        <div class="ft-copy d-flex flex-wrap justify-content-between align-items-center gap-2">
            <span>&copy; {{ date('Y') }} {{ __('common.brand') }}. {{ __('common.footer_rights') }}</span>
            <span class="ft-lang">
                {{ __('common.language') }}:
                @foreach (['id' => 'Indonesia', 'en' => 'English'] as $code => $label)
                    <a href="{{ route('lang.switch', $code) }}"@if ($locale === $code) aria-current="true"@endif>{{ $label }}</a>
                @endforeach
            </span>
        </div>
    </div>
</footer>