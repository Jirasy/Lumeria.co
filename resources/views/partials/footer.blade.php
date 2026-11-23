{{-- Footer gelap: ikon sosial (WhatsApp wajib), kelompok tautan menurut peran, hak cipta. --}}
@php
    $user = auth()->user();
    $wa   = \App\Support\StoreContact::whatsapp(__('foodtech.footer.wa_message'));
    $ig   = \App\Support\StoreContact::instagram();
@endphp
<footer class="ft-footer">
    <div class="container">
        <div class="ft-social" role="group" aria-label="{{ __('foodtech.footer.social') }}">
            @if ($wa)
                <a href="{{ $wa }}" target="_blank" rel="noopener noreferrer" aria-label="{{ __('foodtech.footer.whatsapp') }}">
                    <x-icon name="whatsapp" :size="22" />
                </a>
            @endif
            @if ($ig)
                <a href="{{ $ig['url'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ __('foodtech.footer.instagram') }}">
                    <x-icon name="instagram" :size="22" />
                </a>
            @endif
        </div>

        <div class="row g-4">
            <div class="col-12 col-lg-5">
                <p class="ft-footer-brand">{{ __('common.brand') }}</p>
                <p class="ft-footer-muted mb-3">{{ __('home.tagline') }}</p>
                @if ($wa)
                    <a href="{{ $wa }}" target="_blank" rel="noopener noreferrer" class="btn ft-btn-accent">
                        <x-icon name="whatsapp" :size="18" class="me-2" />{{ __('foodtech.footer.chat_wa') }}
                    </a>
                @endif
            </div>

            <div class="col-6 col-lg-3">
                <h2 class="ft-footer-title">{{ __('foodtech.footer.explore') }}</h2>
                <ul>
                    <li><a href="{{ route('home') }}">{{ __('nav.home') }}</a></li>
                    <li><a href="{{ route('products') }}">{{ __('nav.products') }}</a></li>
                    <li><a href="{{ route('order') }}">{{ __('nav.order') }}</a></li>
                </ul>
            </div>

            <div class="col-6 col-lg-4">
                <h2 class="ft-footer-title">{{ __('foodtech.footer.account') }}</h2>
                <ul>
                    @guest
                        <li><a href="{{ route('login') }}">{{ __('nav.login') }}</a></li>
                        <li><a href="{{ route('register') }}">{{ __('nav.register') }}</a></li>
                    @else
                        <li><a href="{{ route('orders.index') }}">{{ __('foodtech.nav.my_orders') }}</a></li>
                        <li><a href="{{ route('dashboard') }}">{{ __('nav.dashboard') }}</a></li>
<<<<<<< HEAD
                        <li><a href="{{ route('profile.edit') }}">{{ __('Profile') }}</a></li>
=======
>>>>>>> origin/main
                        @if ($user->isAdmin())
                            <li><a href="{{ route('admin.dashboard') }}">{{ __('foodtech.nav.admin') }}</a></li>
                        @endif
                    @endguest
                </ul>
            </div>
        </div>

        <div class="ft-copy">&copy; {{ date('Y') }} {{ __('common.brand') }}. {{ __('common.footer_rights') }}</div>
    </div>
</footer>
