<x-app-layout>
    <x-slot:title>{{ __('products_admin.create_title') }}</x-slot:title>

    <x-slot:header>
        <a href="{{ route('admin.products.index') }}" class="small">{{ __('products_admin.back') }}</a>
        <h1 class="h2 fw-bold mt-1 mb-0">{{ __('products_admin.create_title') }}</h1>
    </x-slot:header>

    <div class="card account-section">
        <div class="card-body p-4">
            @include('admin.products._form', ['action' => route('admin.products.store'), 'method' => 'post'])
        </div>
    </div>
</x-app-layout>
