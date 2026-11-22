<x-app-layout>
    <x-slot:title>{{ __('Profile') }}</x-slot:title>

    <x-slot:header>
        <h1 class="h2 fw-bold mb-0">{{ __('Profile') }}</h1>
    </x-slot:header>

    <div class="row">
        <div class="col-lg-8 col-xl-7 d-grid gap-4">
            <div class="card account-section">
                <div class="card-body p-4">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="card account-section">
                <div class="card-body p-4">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="card account-section account-section-danger">
                <div class="card-body p-4">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
