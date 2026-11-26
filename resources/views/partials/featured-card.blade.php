{{-- Kartu editorial beranda: foto besar tanpa bingkai, nama, kategori, dan harga. --}}
<a href="{{ route('products.show', $product) }}" class="f-card">
    <div class="f-card-media">
        @if ($product->badge_label)
            <span class="ft-badge ft-badge-{{ $product->badge_tone }}">{{ $product->badge_label }}</span>
        @endif

        @if ($product->image)
            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" loading="lazy" width="1024" height="1024">
        @else
            <x-product-art :slug="$product->slug" :size="120" />
        @endif
    </div>

    <div class="f-card-body">
        <div>
            <h3 class="f-card-name">{{ $product->name }}</h3>
            <span class="f-card-cat">{{ __('products.categories.' . $product->category) }}</span>
        </div>
        <span class="f-card-price">{{ $product->formatted_price }}</span>
    </div>

    <span class="f-card-arrow">
        {{ __('foodtech.card.detail') }}
        <x-icon name="arrow-right" :size="14" />
    </span>
</a>
