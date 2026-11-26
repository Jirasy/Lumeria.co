<section aria-labelledby="delete-account-title">
    <h2 id="delete-account-title" class="h5 fw-bold text-danger-emphasis mb-1">{{ __('Delete Account') }}</h2>
    <p class="text-muted mb-4">
        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
    </p>

    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-user-deletion">
        {{ __('Delete Account') }}
    </button>

    <div class="modal fade" id="confirm-user-deletion" tabindex="-1" aria-labelledby="confirm-user-deletion-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="post" action="{{ route('profile.destroy') }}" class="modal-content" novalidate>
                @csrf
                @method('delete')

                <div class="modal-header">
                    <h2 id="confirm-user-deletion-title" class="modal-title h5">{{ __('Are you sure you want to delete your account?') }}</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Cancel') }}"></button>
                </div>

                <div class="modal-body">
                    <p class="text-muted">
                        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                    </p>

                    <x-auth.field id="delete_account_password" name="password" type="password" bag="userDeletion"
                        :label="__('Password')" autocomplete="current-password" :lg="false" />
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-danger">{{ __('Delete Account') }}</button>
                </div>
            </form>
        </div>
    </div>
</section>

@push('scripts')
    <script>
        (function () {
            var modalEl = document.getElementById('confirm-user-deletion');
            if (!modalEl || !window.bootstrap) { return; }

            // Fokus ke kolom kata sandi setiap kali modal terbuka.
            modalEl.addEventListener('shown.bs.modal', function () {
                var input = document.getElementById('delete_account_password');
                if (input) { input.focus(); }
            });

            @if ($errors->userDeletion->isNotEmpty())
                // Buka kembali modal agar pesan error terlihat.
                new bootstrap.Modal(modalEl).show();
            @endif
        })();
    </script>
@endpush
