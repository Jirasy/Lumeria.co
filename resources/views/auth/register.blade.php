<x-guest-layout>
    <x-slot:title>{{ __('Register') }}</x-slot:title>

    <x-slot:tabs>
        <a class="auth-tab" href="{{ route('login') }}">{{ __('nav.login') }}</a>
        <a class="auth-tab is-active" href="{{ route('register') }}" aria-current="page">{{ __('nav.register') }}</a>
    </x-slot:tabs>

    <h1 class="auth-title">{{ __('auth.register_title') }}</h1>
    <p class="auth-subtitle">{{ __('auth.register_subtitle') }}</p>

    {{-- Hanya name, email, password, password_confirmation. Peran akun diisi server (customer); tidak ada kolom role. --}}
    <form method="POST" action="{{ route('register') }}" novalidate>
        @csrf

        <x-auth.field name="name" type="text" :label="__('Name')" :value="old('name')" autocomplete="name" autofocus />

        <x-auth.field name="email" type="email" :label="__('Email')" :value="old('email')" autocomplete="username" inputmode="email" />

        <x-auth.field name="password" type="password" :label="__('Password')" :hint="__('auth.password_hint')" autocomplete="new-password" :reveal="true" />

        <div class="pw-strength" data-password-strength data-target="password" data-level="0"
             data-empty-label="{{ __('auth.strength_empty') }}"
             data-labels="{{ __('auth.strength_weak') }}|{{ __('auth.strength_fair') }}|{{ __('auth.strength_good') }}|{{ __('auth.strength_strong') }}">
            <div class="pw-strength-track" aria-hidden="true">
                <span class="pw-strength-bar"></span>
                <span class="pw-strength-bar"></span>
                <span class="pw-strength-bar"></span>
                <span class="pw-strength-bar"></span>
            </div>
            <p class="pw-strength-text">
                <span class="visually-hidden">{{ __('auth.strength_label') }}: </span>
                <span data-strength-text>{{ __('auth.strength_empty') }}</span>
            </p>
        </div>

        <x-auth.field name="password_confirmation" type="password" :label="__('Confirm Password')" autocomplete="new-password" :reveal="true" />

        <p class="pw-match" data-password-match data-source="password" data-target="password_confirmation" data-state="idle"
           data-ok-label="{{ __('auth.match_ok') }}" data-bad-label="{{ __('auth.match_bad') }}"></p>

        <button type="submit" class="btn btn-lumeria auth-submit mt-2">
            {{ __('Register') }}
            <x-icon name="arrow-right" :size="16" />
        </button>
    </form>

    <p class="auth-alt mb-0">
        {{ __('auth.have_account') }}
        <a href="{{ route('login') }}">{{ __('nav.login') }}</a>
    </p>
</x-guest-layout>
