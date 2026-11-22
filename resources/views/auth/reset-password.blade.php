<x-guest-layout>
    <x-slot:title>{{ __('Reset Password') }}</x-slot:title>

    <h1 class="h2 fw-bold mb-4">{{ __('Reset Password') }}</h1>

    <form method="POST" action="{{ route('password.store') }}" novalidate>
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <x-auth.field name="email" type="email" :label="__('Email')" :value="old('email', $request->email)" autocomplete="username" autofocus />

        <x-auth.field name="password" type="password" :label="__('Password')" :hint="__('auth.password_hint')" autocomplete="new-password" />

        <x-auth.field name="password_confirmation" type="password" :label="__('Confirm Password')" autocomplete="new-password" />

        <button type="submit" class="btn btn-lumeria btn-lg w-100 mt-2">{{ __('Reset Password') }}</button>
    </form>
</x-guest-layout>
