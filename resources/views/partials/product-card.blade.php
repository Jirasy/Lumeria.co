@php
    $emoji = [
        'original-milk'           => '🥛',
        'bola-teh'                => '🍵',
        'bola-kopi'               => '☕',
        'bolu-lumer-cokelat-keju' => '🍰',
        'kue-balok-lumer'         => '🧇',
        'es-krim-lumeria-creamy'  => '🍦',
    ][$product->slug] ?? '🍬';
@endphp
<div class="card h-100 shadow-sm product-card">
    <div class="card-body d-flex flex-column">
        <div class="product-emoji mb-2" aria-hidden="true">{{ $emoji }}</div>

        <div class="d-flex justify-content-between align-items-start gap-2">
            <h3 class="h5 card-title mb-1">{{ $product->name }}</h3>
            @if ($product->is_signature)
                <span class="badge text-bg-warning">{{ __('products.signature_badge') }}</span>
            @endif
        </div>

        <p class="card-text text-muted flex-grow-1">{{ $product->description }}</p>
        <p class="fs-5 fw-bold mb-3">{{ $product->formatted_price }}</p>

        <form method="POST" action="{{ route('cart.add') }}" class="d-flex gap-2">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <label for="qty-{{ $product->id }}" class="visually-hidden">{{ __('products.qty') }}</label>
            <input id="qty-{{ $product->id }}" type="number" name="quantity" value="1" min="1" max="99"
                   class="form-control" style="max-width: 5.5rem">
            <button type="submit" class="btn btn-lumeria flex-grow-1">{{ __('products.add') }}</button>
        </form>
    </div>
</div>