<div id="product-{{ $product->id }}" class="card h-100 border-0 shadow-sm product-card overflow-hidden">
    @if ($product->image)
        <div class="product-art">
            <img src="{{ asset($product->image) }}" alt="{{ $product->display_name }}" class="w-100 h-100 object-fit-cover" loading="lazy">
        </div>
    @else
        <x-product-art :slug="$product->slug" :size="104" />
    @endif

    <div class="card-body d-flex flex-column">
        <div class="d-flex justify-content-between align-items-start gap-2">
            <h3 class="h5 card-title mb-1">{{ $product->display_name }}</h3>
            @if ($product->is_signature)
                <span class="badge rounded-pill badge-lumeria">{{ __('products.signature_badge') }}</span>
            @endif
        </div>

        <p class="card-text text-muted flex-grow-1">{{ $product->description }}</p>
        <p class="fs-5 fw-bold mb-3">{{ $product->formatted_price }}</p>

        <form method="POST" action="{{ route('cart.add') }}" class="d-flex flex-wrap gap-2">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <label for="qty-{{ $product->id }}" class="visually-hidden">{{ __('products.qty') }}</label>
            <input id="qty-{{ $product->id }}" type="number" name="quantity" value="1" min="1" max="99"
                   class="form-control" style="max-width: 5.5rem">

            @auth
                <button type="submit" class="btn btn-lumeria flex-grow-1">{{ __('products.add') }}</button>
            @else
                <button type="submit" class="btn btn-outline-dark flex-grow-1">{{ __('products.login_to_add') }}</button>
                <div class="w-100 small text-muted">{{ __('order.login_required') }}</div>
            @endauth
        </form>
    </div>
</div>