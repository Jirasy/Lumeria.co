{{-- Menu samping profil: identitas singkat pengguna + tautan ke bagian halaman dan ke Pesanan Saya. --}}
<div class="profile-side">
    <div class="d-flex align-items-center gap-3 pb-3 mb-3 border-bottom">
        <x-user-avatar :user="$user" :size="56" class="flex-shrink-0" />
        <div class="min-w-0">
            <div class="fw-bold text-truncate">{{ $user->name }}</div>
            <a href="#profil" class="small text-muted">{{ __('profile.edit_profile') }}</a>
        </div>
    </div>

    <nav aria-label="{{ __('profile.my_account') }}">
        <p class="fw-bold mb-1">{{ __('profile.my_account') }}</p>
        <ul class="profile-nav">
            <li><a href="#profil">{{ __('profile.nav_profile') }}</a></li>
            <li><a href="#kata-sandi">{{ __('profile.nav_password') }}</a></li>
            <li><a href="#hapus-akun">{{ __('profile.nav_delete') }}</a></li>
            <li class="profile-nav-main"><a href="{{ route('orders.index') }}" class="fw-bold">{{ __('foodtech.nav.my_orders') }}</a></li>
        </ul>
    </nav>
</div>
