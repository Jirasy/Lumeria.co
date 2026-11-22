<section aria-labelledby="update-password-title">
    <h2 id="update-password-title" class="h5 fw-bold mb-1">{{ __('Update Password') }}</h2>
    <p class="text-muted mb-4">{{ __('Ensure your account is using a long, random password to stay secure.') }}</p>

    <form method="post" action="{{ route('password.update') }}" novalidate>
        @csrf
        @method('put')

        <x-auth.field id="update_password_current_password" name="current_password" type="password" bag="updatePassword"
            :label="__('Current Password')" autocomplete="current-password" :lg="false" />

        <x-auth.field id="update_password_password" name="password" type="password" bag="updatePassword"
            :label="__('New Password')" :hint="__('auth.password_hint')" autocomplete="new-password" :lg="false" />

        <x-auth.field id="update_password_password_confirmation" name="password_confirmation" type="password" bag="updatePassword"
            :label="__('Confirm Password')" autocomplete="new-password" :lg="false" />

        <div class="d-flex flex-wrap align-items-center gap-3">
            <button type="submit" class="btn btn-lumeria">{{ __('Save') }}</button>

            @if (session('status') === 'password-updated')
                <span class="text-success-emphasis fw-semibold" role="status">{{ __('Saved.') }}</span>
            @endif
        </div>
    </form>
</section>
