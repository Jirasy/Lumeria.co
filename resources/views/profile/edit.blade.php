<x-app-layout>
    <x-slot:title>{{ __('profile.title') }}</x-slot:title>

    <div class="row g-4">
        <aside class="col-lg-3">
            @include('profile.partials.sidebar')
        </aside>

        <div class="col-lg-9 d-grid gap-4">
            <div id="profil" class="card account-section">
                <div class="card-body p-4">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div id="kata-sandi" class="card account-section">
                <div class="card-body p-4">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div id="hapus-akun" class="card account-section account-section-danger">
                <div class="card-body p-4">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
