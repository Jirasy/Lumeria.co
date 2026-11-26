{{-- Form tambah/ubah produk. Variabel: $product, $action, $method ('post' | 'put') --}}
@php
    $old     = session()->hasOldInput();
    $checked = fn (string $key, bool $default) => $old ? (bool) old($key) : $default;
    $badges  = collect(\App\Models\Product::BADGES)->mapWithKeys(fn ($b) => [$b => __('foodtech.badges.' . $b)])->all();
    $cats    = collect(\App\Models\Product::CATEGORIES)->mapWithKeys(fn ($c) => [$c => __('products.categories.' . $c)])->all();
@endphp
<form method="post" action="{{ $action }}" enctype="multipart/form-data" novalidate>
    @csrf
    @if ($method !== 'post') @method($method) @endif

    <div class="row g-4">
        {{-- Foto produk: di atas pada layar kecil, di kanan pada layar besar --}}
        <div class="col-lg-4 order-first order-lg-last">
            <div class="text-center">
                <div id="product-image-preview" class="product-photo-preview mx-auto mb-3">
                    @if ($product->image)
                        <img src="{{ asset($product->image) }}" alt="" width="160" height="160">
                    @else
                        <x-product-art :slug="$product->slug ?: 'baru'" :size="120" :bare="true" />
                    @endif
                </div>

                <input id="image" name="image" type="file" class="visually-hidden profile-file"
                       accept="image/png,image/jpeg,image/webp" data-max-bytes="{{ \App\Models\Product::IMAGE_MAX_KB * 1024 }}"
                       data-too-big="{{ __('products_admin.image_too_big') }}"
                       aria-describedby="image-help{{ $errors->has('image') ? ' image-error' : '' }}"
                       @if ($errors->has('image')) aria-invalid="true" @endif>
                <label for="image" class="btn btn-outline-dark">{{ __('products_admin.choose_image') }}</label>

                <p id="image-help" class="text-muted small mt-3 mb-0">
                    {{ __('products_admin.image_hint') }}
                    @unless ($product->image)
                        <span id="image-none"><br>{{ __('products_admin.image_none') }}</span>
                    @endunless
                </p>

                <div id="image-error" class="text-danger small mt-2" @if (! $errors->has('image')) hidden @endif>
                    @foreach ($errors->get('image') as $message)
                        <div>{{ $message }}</div>
                    @endforeach
                </div>

                @if ($product->image)
                    <div class="form-check d-inline-block text-start mt-3">
                        <input class="form-check-input" type="checkbox" id="remove_image" name="remove_image" value="1" @checked($old && old('remove_image'))>
                        <label class="form-check-label" for="remove_image">{{ __('products_admin.remove_image') }}</label>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-lg-8">
            <x-auth.field name="name" type="text" :label="__('products_admin.field_name')" :value="old('name', $product->name)" maxlength="100" autocomplete="off" :lg="false" />

            <div class="row g-3">
                <div class="col-md-6">
                    <x-auth.select name="category" :label="__('products_admin.field_category')" :options="$cats" :selected="old('category', $product->category)" />
                </div>
                <div class="col-md-6">
                    <x-auth.field name="price" type="text" :label="__('products_admin.field_price')" :value="old('price', $product->price)"
                        :hint="__('products_admin.price_hint')" inputmode="numeric" autocomplete="off" :lg="false" />
                </div>
            </div>

            <x-auth.textarea name="description_id" :label="__('products_admin.field_description_id')" :value="old('description_id', $product->description_id)"
                :hint="__('products_admin.description_hint')" maxlength="1000" />

            <x-auth.textarea name="description_en" :label="__('products_admin.field_description_en')" :value="old('description_en', $product->description_en)"
                :hint="__('products_admin.description_hint')" maxlength="1000" />

            <x-auth.select name="badge" :label="__('products_admin.field_badge')" :options="$badges" :selected="old('badge', $product->badge)"
                :placeholder="__('products_admin.no_badge')" :required="false" />

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="is_signature" name="is_signature" value="1" aria-describedby="is_signature-hint"
                       @checked($checked('is_signature', (bool) $product->is_signature))>
                <label class="form-check-label fw-semibold" for="is_signature">{{ __('products_admin.field_signature') }}</label>
                <div id="is_signature-hint" class="form-text mt-0">{{ __('products_admin.signature_hint') }}</div>
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" aria-describedby="is_active-hint"
                       @checked($checked('is_active', (bool) $product->is_active))>
                <label class="form-check-label fw-semibold" for="is_active">{{ __('products_admin.field_active') }}</label>
                <div id="is_active-hint" class="form-text mt-0">{{ __('products_admin.active_hint') }}</div>
            </div>

            @if ($product->exists)
                <p class="small text-muted mb-3">
                    <span class="fw-semibold">{{ __('products_admin.slug') }}:</span> <code>/products/{{ $product->slug }}</code><br>
                    {{ __('products_admin.slug_hint') }}
                </p>
            @endif

            <div class="d-flex flex-wrap gap-2 mt-4">
                <button type="submit" class="btn btn-lumeria px-4">{{ __('products_admin.save') }}</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-dark">{{ __('Cancel') }}</a>
            </div>
        </div>
    </div>
</form>

@push('scripts')
    <script>
        (function () {
            var input = document.getElementById('image');
            var holder = document.getElementById('product-image-preview');
            var error = document.getElementById('image-error');
            if (!input || !holder) { return; }

            input.addEventListener('change', function () {
                var file = input.files && input.files[0];
                if (!file) { return; }

                // Batas 1 MB juga dicek di server; ini hanya agar pesan muncul lebih cepat.
                if (file.size > Number(input.dataset.maxBytes)) {
                    input.value = '';
                    error.textContent = input.dataset.tooBig;
                    error.hidden = false;
                    return;
                }

                error.hidden = true;
                error.textContent = '';

                var none = document.getElementById('image-none');
                if (none) { none.hidden = true; }

                var img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.alt = '';
                img.width = 160;
                img.height = 160;
                holder.replaceChildren(img);
            });
        })();
    </script>
@endpush
