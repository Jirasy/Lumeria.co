{{-- Kartu produk etalase: badge pojok (promo/diskon/terlaris/baru) + tombol langsung ke halaman detail. --}}
<article id="product-{{ $product->id }}" class="card h-100 border-0 product-card">
    <div class="ft-media">
        @if (! empty($product->badge_label))
            <span class="ft-badge ft-badge-{{ $product->badge_tone ?? 'promo' }}">{{ $product->badge_label }}</span>
        @endif

        @if ($product->image)
            <div class="product-art">
                <img src="{{ asset($product->image) }}" alt="{{ $product->display_name }}" loading="lazy" width="1024" height="1024">
            </div>
        @else
            <x-product-art :slug="$product->slug" :size="104" />
        @endif
    </div>

    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start gap-2">
            <h3 class="card-title mb-0">{{ $product->display_name }}</h3>
            @if ($product->is_signature)
                <span class="badge rounded-pill badge-lumeria">{{ __('products.signature_badge') }}</span>
            @endif
        </div>

        <p class="card-text text-muted ft-clamp">{{ $product->description }}</p>

        <div class="d-flex align-items-center justify-content-between gap-2 mt-auto">
            <p class="fs-5 fw-bold mb-0">{{ $product->formatted_price }}</p>
            <a href="{{ route('products.show', $product) }}" class="link-arrow">
                {{ __('foodtech.card.detail') }}<span class="visually-hidden">: {{ $product->display_name }}</span>
                <x-icon name="arrow-right" :size="14" />
            </a>
        </div>

        <div class="mt-3">
            @auth
                <form method="POST" action="{{ route('cart.add') }}" class="d-flex gap-2">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <label for="qty-{{ $product->id }}" class="visually-hidden">{{ __('products.qty') }}</label>
                    <input id="qty-{{ $product->id }}" type="number" name="quantity" value="1" min="1" max="99"
                           class="form-control" aria-label="{{ __('products.qty') }}">
                    <button type="submit" class="btn btn-outline-dark flex-grow-1">{{ __('products.add') }}</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-dark w-100">{{ __('foodtech.detail.login_to_order') }}</a>
            @endauth
        </div>
    </div>
</article>
