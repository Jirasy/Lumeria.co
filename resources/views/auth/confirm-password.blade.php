<x-guest-layout>
    <x-slot:title>{{ __('Confirm Password') }}</x-slot:title>

    <h1 class="h2 fw-bold mb-2">{{ __('Confirm Password') }}</h1>
    <p class="text-muted mb-4">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </p>

    <form method="POST" action="{{ route('password.confirm') }}" novalidate>
        @csrf

        <x-auth.field name="password" type="password" :label="__('Password')" autocomplete="current-password" autofocus />

        <button type="submit" class="btn btn-lumeria btn-lg w-100 mt-2">{{ __('Confirm') }}</button>
    </form>
</x-guest-layout>
