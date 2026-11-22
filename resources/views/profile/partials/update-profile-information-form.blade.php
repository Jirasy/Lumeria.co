<section aria-labelledby="profile-information-title">
    <h2 id="profile-information-title" class="h5 fw-bold mb-1">{{ __('Profile Information') }}</h2>
    <p class="text-muted mb-4">{{ __("Update your account's profile information and email address.") }}</p>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" novalidate>
        @csrf
        @method('patch')

        <x-auth.field name="name" type="text" :label="__('Name')" :value="old('name', $user->name)" autocomplete="name" :lg="false" />

        <x-auth.field name="email" type="email" :label="__('Email')" :value="old('email', $user->email)" autocomplete="username" :lg="false" />

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="alert alert-warning" role="status">
                {{ __('Your email address is unverified.') }}
                <button type="submit" form="send-verification" class="btn btn-link p-0 align-baseline">
                    {{ __('Click here to re-send the verification email.') }}
                </button>
            </div>

            @if (session('status') === 'verification-link-sent')
                <div class="alert alert-success" role="status">
                    {{ __('A new verification link has been sent to your email address.') }}
                </div>
            @endif
        @endif

        <div class="d-flex flex-wrap align-items-center gap-3">
            <button type="submit" class="btn btn-lumeria">{{ __('Save') }}</button>

            @if (session('status') === 'profile-updated')
                <span class="text-success-emphasis fw-semibold" role="status">{{ __('Saved.') }}</span>
            @endif
        </div>
    </form>
</section>
