<x-guest-layout>
    <x-slot:title>{{ __('Register') }}</x-slot:title>

    <h1 class="h2 fw-bold mb-1">{{ __('auth.register_title') }}</h1>
    <p class="text-muted mb-4">{{ __('auth.register_subtitle') }}</p>

    {{-- Hanya name, email, password, password_confirmation. Peran akun diisi server (customer); tidak ada kolom role. --}}
    <form method="POST" action="{{ route('register') }}" novalidate>
        @csrf

        <x-auth.field name="name" type="text" :label="__('Name')" :value="old('name')" autocomplete="name" autofocus />

        <x-auth.field name="email" type="email" :label="__('Email')" :value="old('email')" autocomplete="username" />

        <x-auth.field name="password" type="password" :label="__('Password')" :hint="__('auth.password_hint')" autocomplete="new-password" />

        <x-auth.field name="password_confirmation" type="password" :label="__('Confirm Password')" autocomplete="new-password" />

        <button type="submit" class="btn btn-lumeria btn-lg w-100 mt-2">{{ __('Register') }}</button>
    </form>

    <p class="text-center mt-4 mb-0">
        {{ __('auth.have_account') }}
        <a href="{{ route('login') }}" class="fw-semibold">{{ __('nav.login') }}</a>
    </p>
</x-guest-layout>
