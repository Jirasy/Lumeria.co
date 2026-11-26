<x-app-layout>
    <x-slot:title>{{ __('users.create_title') }}</x-slot:title>

    <x-slot:header>
        <a href="{{ route('admin.users.index') }}" class="small">{{ __('users.back') }}</a>
        <h1 class="h2 fw-bold mt-1 mb-0">{{ __('users.create_title') }}</h1>
    </x-slot:header>

    <div class="row">
        <div class="col-lg-7 col-xl-6">
            <div class="card account-section">
                <div class="card-body p-4">
                    @include('admin.users._form', ['action' => route('admin.users.store'), 'method' => 'post'])
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
