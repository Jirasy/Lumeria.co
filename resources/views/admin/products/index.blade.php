<x-app-layout>
    <x-slot:title>{{ __('products_admin.title') }}</x-slot:title>

    <x-slot:header>
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
            <div>
                <h1 class="h2 fw-bold mb-1">{{ __('products_admin.title') }}</h1>
                <p class="text-muted mb-0">{{ __('products_admin.subtitle') }}</p>
            </div>
            <a href="{{ route('admin.products.create') }}" class="btn btn-lumeria">{{ __('products_admin.add') }}</a>
        </div>
    </x-slot:header>

    @if (session('status'))
        <div class="alert alert-success" role="status">{{ session('status') }}</div>
    @endif

    <div class="card account-section">
        <div class="card-body p-3 p-md-4">
            <form method="get" action="{{ route('admin.products.index') }}" class="row g-2 align-items-end mb-4">
                <div class="col-12 col-md">
                    <label for="q" class="form-label fw-semibold">{{ __('products_admin.search_label') }}</label>
                    <input id="q" name="q" type="search" value="{{ $search }}" class="form-control">
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <label for="category" class="form-label fw-semibold">{{ __('products_admin.filter_category') }}</label>
                    <select id="category" name="category" class="form-select">
                        <option value="">{{ __('products_admin.all_categories') }}</option>
                        @foreach (\App\Models\Product::CATEGORIES as $c)
                            <option value="{{ $c }}" @selected($category === $c)>{{ __('products.categories.' . $c) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-md-2">
                    <label for="status" class="form-label fw-semibold">{{ __('products_admin.filter_status') }}</label>
                    <select id="status" name="status" class="form-select">
                        <option value="">{{ __('products_admin.all_status') }}</option>
                        <option value="active" @selected($status === 'active')>{{ __('products_admin.status_active') }}</option>
                        <option value="inactive" @selected($status === 'inactive')>{{ __('products_admin.status_inactive') }}</option>
                    </select>
                </div>
                <div class="col-12 col-md-auto d-flex gap-2">
                    <button type="submit" class="btn btn-lumeria flex-fill">{{ __('products_admin.search') }}</button>
                    @if ($search !== '' || $category !== '' || $status !== '')
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-dark">{{ __('products_admin.reset') }}</a>
                    @endif
                </div>
            </form>

            <p class="text-muted small mb-2">{{ __('products_admin.total', ['count' => $products->total()]) }}</p>

            @if ($products->isEmpty())
                <p class="mb-0">{{ __('products_admin.empty') }}</p>
            @else
                <div class="table-responsive">
                    <table class="table align-middle stack-table mb-0">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('products_admin.col_product') }}</th>
                                <th scope="col">{{ __('products_admin.col_category') }}</th>
                                <th scope="col">{{ __('products_admin.col_price') }}</th>
                                <th scope="col">{{ __('products_admin.col_status') }}</th>
                                <th scope="col"><span class="visually-hidden">{{ __('products_admin.col_actions') }}</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $p)
                                <tr>
                                    <td data-label="{{ __('products_admin.col_product') }}">
                                        <span class="d-inline-flex align-items-center gap-2">
                                            <span class="admin-thumb flex-shrink-0" aria-hidden="true">
                                                @if ($p->image)
                                                    <img src="{{ asset($p->image) }}" alt="" width="44" height="44" loading="lazy">
                                                @else
                                                    <x-product-art :slug="$p->slug" :size="40" :bare="true" />
                                                @endif
                                            </span>
                                            <span class="min-w-0">
                                                <span class="fw-semibold d-block text-break">{{ $p->name }}</span>
                                                @if ($p->badge_label)
                                                    <span class="badge text-bg-light border">{{ $p->badge_label }}</span>
                                                @endif
                                                @if ($p->is_signature)
                                                    <span class="badge text-bg-warning">{{ __('products.signature_badge') }}</span>
                                                @endif
                                            </span>
                                        </span>
                                    </td>
                                    <td data-label="{{ __('products_admin.col_category') }}">{{ __('products.categories.' . $p->category) }}</td>
                                    <td data-label="{{ __('products_admin.col_price') }}">{{ $p->formatted_price }}</td>
                                    <td data-label="{{ __('products_admin.col_status') }}">
                                        <span class="badge {{ $p->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">
                                            {{ $p->is_active ? __('products_admin.status_active') : __('products_admin.status_inactive') }}
                                        </span>
                                    </td>
                                    <td data-label="" class="text-md-end">
                                        <div class="d-inline-flex flex-wrap gap-2 justify-content-md-end">
                                            @if ($p->is_active)
                                                <a href="{{ route('products.show', $p) }}" class="btn btn-sm btn-outline-dark">{{ __('products_admin.view_public') }}</a>
                                            @endif
                                            <a href="{{ route('admin.products.edit', $p) }}" class="btn btn-sm btn-outline-dark">{{ __('products_admin.edit') }}</a>
                                            <form method="post" action="{{ route('admin.products.destroy', $p) }}"
                                                  onsubmit="return confirm(@js(__('products_admin.confirm_delete', ['name' => $p->name])));">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">{{ __('products_admin.delete') }}</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
