<x-guest-layout>
    <x-slot:title>{{ __('Log in') }}</x-slot:title>

    <x-slot:tabs>
        <a class="auth-tab is-active" href="{{ route('login') }}" aria-current="page">{{ __('nav.login') }}</a>
        <a class="auth-tab" href="{{ route('register') }}">{{ __('nav.register') }}</a>
    </x-slot:tabs>

    <h1 class="auth-title">{{ __('auth.login_title') }}</h1>
    <p class="auth-subtitle">{{ __('auth.login_subtitle') }}</p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" novalidate>
        @csrf

        <x-auth.field name="email" type="email" :label="__('Email')" :value="old('email')" autocomplete="username" inputmode="email" autofocus />

        <x-auth.field name="password" type="password" :label="__('Password')" autocomplete="current-password" :reveal="true" />

        <div class="auth-actions">
            <div class="form-check mb-0">
                <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                <label for="remember_me" class="form-check-label">{{ __('Remember me') }}</label>
            </div>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="auth-link">{{ __('Forgot your password?') }}</a>
            @endif
        </div>

        <button type="submit" class="btn btn-lumeria auth-submit">
            {{ __('Log in') }}
            <x-icon name="arrow-right" :size="16" />
        </button>
    </form>

    <p class="auth-alt mb-0">
        {{ __('auth.no_account') }}
        <a href="{{ route('register') }}">{{ __('nav.register') }}</a>
    </p>
</x-guest-layout>
