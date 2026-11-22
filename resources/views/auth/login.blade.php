<x-guest-layout>
    <x-slot:title>{{ __('Log in') }}</x-slot:title>

    <h1 class="h2 fw-bold mb-1">{{ __('auth.login_title') }}</h1>
    <p class="text-muted mb-4">{{ __('auth.login_subtitle') }}</p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" novalidate>
        @csrf

        <x-auth.field name="email" type="email" :label="__('Email')" :value="old('email')" autocomplete="username" autofocus />

        <x-auth.field name="password" type="password" :label="__('Password')" autocomplete="current-password" />

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
            <div class="form-check mb-0">
                <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                <label for="remember_me" class="form-check-label">{{ __('Remember me') }}</label>
            </div>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a>
            @endif
        </div>

        <button type="submit" class="btn btn-lumeria btn-lg w-100">{{ __('Log in') }}</button>
    </form>

    <p class="text-center mt-4 mb-0">
        {{ __('auth.no_account') }}
        <a href="{{ route('register') }}" class="fw-semibold">{{ __('nav.register') }}</a>
    </p>
</x-guest-layout>
