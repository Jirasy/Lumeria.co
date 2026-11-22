{{-- Kartu produk etalase: badge pojok (promo/diskon/terlaris/baru) + tombol langsung ke halaman detail. --}}
<div id="product-{{ $product->id }}" class="card h-100 border-0 shadow-sm product-card overflow-hidden">
    <div class="ft-media">
        @if ($product->badge_label)
            <span class="ft-badge ft-badge-{{ $product->badge_tone }}">{{ $product->badge_label }}</span>
        @endif

        @if ($product->image)
            <div class="product-art">
                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-100 h-100 object-fit-cover" loading="lazy">
            </div>
        @else
            <x-product-art :slug="$product->slug" :size="104" />
        @endif
    </div>

    <div class="card-body d-flex flex-column">
        <div class="d-flex justify-content-between align-items-start gap-2">
            <h3 class="h5 card-title mb-1">{{ $product->name }}</h3>
            @if ($product->is_signature)
                <span class="badge rounded-pill badge-lumeria">{{ __('products.signature_badge') }}</span>
            @endif
        </div>

        <p class="card-text text-muted flex-grow-1 ft-clamp">{{ $product->description }}</p>
        <p class="fs-5 fw-bold mb-3">{{ $product->formatted_price }}</p>

        <a href="{{ route('products.show', $product) }}" class="btn btn-lumeria w-100">
            {{ __('foodtech.card.detail') }}<span class="visually-hidden">: {{ $product->name }}</span>
        </a>

        @auth
            <form method="POST" action="{{ route('cart.add') }}" class="d-flex gap-2 mt-2">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <label for="qty-{{ $product->id }}" class="visually-hidden">{{ __('products.qty') }}</label>
                <input id="qty-{{ $product->id }}" type="number" name="quantity" value="1" min="1" max="99"
                       class="form-control" style="max-width: 5.5rem">
                <button type="submit" class="btn btn-outline-dark flex-grow-1">{{ __('products.add') }}</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="btn btn-outline-dark w-100 mt-2">{{ __('foodtech.detail.login_to_order') }}</a>
        @endauth
    </div>
</div>
