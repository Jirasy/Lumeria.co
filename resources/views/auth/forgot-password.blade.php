<x-guest-layout>
    <x-slot:title>{{ __('Reset Password') }}</x-slot:title>

    <h1 class="h2 fw-bold mb-2">{{ __('Reset Password') }}</h1>
    <p class="text-muted mb-4">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" novalidate>
        @csrf

        <x-auth.field name="email" type="email" :label="__('Email')" :value="old('email')" autocomplete="username" autofocus />

        <button type="submit" class="btn btn-lumeria btn-lg w-100 mt-2">{{ __('Email Password Reset Link') }}</button>
    </form>

    <p class="text-center mt-4 mb-0">
        <a href="{{ route('login') }}" class="fw-semibold">{{ __('Log in') }}</a>
    </p>
</x-guest-layout>
