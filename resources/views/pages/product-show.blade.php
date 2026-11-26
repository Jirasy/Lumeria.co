@extends('layouts.lumeria')

@section('title', $product->name)
@section('description', $product->description)

@section('content')
    @php
        $wa = \App\Support\StoreContact::whatsapp(__('foodtech.detail.wa_message', ['product' => $product->name]));
    @endphp

    <section class="section section--tight">
        <div class="shell">
            <nav aria-label="breadcrumb" class="mb-4 small text-muted">
                <a href="{{ route('home') }}" class="plain-link">{{ __('nav.home') }}</a>
                <span aria-hidden="true">/</span>
                <a href="{{ route('products') }}" class="plain-link">{{ __('products.title') }}</a>
                <span aria-hidden="true">/</span>
                <span>{{ $product->name }}</span>
            </nav>

            {{-- id="product-{id}" dipakai CartController untuk kembali ke posisi ini setelah "Tambah ke Keranjang" --}}
            <div id="product-{{ $product->id }}" class="row g-4 g-lg-5">
                <div class="col-lg-6">
                    <div class="ft-detail-sticky">
                        <div class="ft-media ft-detail-media">
                            @if ($product->badge_label)
                                <span class="ft-badge ft-badge-{{ $product->badge_tone }}">{{ $product->badge_label }}</span>
                            @endif

                            @if ($product->image)
                                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" width="1024" height="1024">
                            @else
                                <x-product-art :slug="$product->slug" :size="260" />
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="ft-detail-sticky">
                        <p class="pdp-cat">{{ __('products.categories.' . $product->category) }}</p>

                        <h1 class="pdp-title">
                            {{ $product->name }}
                            @if ($product->is_signature)
                                <span class="badge rounded-pill badge-lumeria">{{ __('products.signature_badge') }}</span>
                            @endif
                        </h1>

                        <p class="pdp-price">{{ $product->formatted_price }}</p>
                        <p class="pdp-desc">{{ $product->description }}</p>

                        @auth
                            <form method="POST" action="{{ route('cart.add') }}" class="d-flex gap-2 mt-4" style="max-width: 26rem">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <label for="qty-{{ $product->id }}" class="visually-hidden">{{ __('products.qty') }}</label>
                                <input id="qty-{{ $product->id }}" type="number" name="quantity" value="1" min="1" max="99"
                                       class="form-control" style="max-width: 5.5rem">
                                <button type="submit" class="btn btn-lumeria flex-grow-1">{{ __('products.add') }}</button>
                            </form>
                        @else
                            <div class="ft-note mt-4">
                                <p class="mb-3">{{ __('foodtech.detail.guest_note') }}</p>
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ route('login') }}" class="btn btn-lumeria">{{ __('foodtech.detail.login_to_order') }}</a>
                                    <a href="{{ route('register') }}" class="btn btn-outline-dark">{{ __('foodtech.detail.register') }}</a>
                                </div>
                            </div>
                        @endauth

                        <ul class="pdp-trust">
                            <li><x-icon name="check" :size="16" />{{ __('home.hero_point_1') }}</li>
                            <li><x-icon name="check" :size="16" />{{ __('home.hero_point_2') }}</li>
                            <li><x-icon name="check" :size="16" />{{ __('home.hero_point_3') }}</li>
                        </ul>

                        <div class="d-flex flex-wrap gap-3 mt-4">
                            @if ($wa)
                                <a href="{{ $wa }}" target="_blank" rel="noopener noreferrer" class="d-inline-flex align-items-center gap-2 fw-semibold plain-link">
                                    <x-icon name="whatsapp" :size="18" />{{ __('foodtech.detail.order_wa') }}
                                </a>
                            @endif
                            <a href="{{ route('order', ['amount' => $product->price]) }}" class="fw-semibold plain-link">{{ __('foodtech.detail.paylater') }}</a>
                        </div>
                    </div>
                </div>
            </div>

            @if ($related->isNotEmpty())
                <div class="section-head mt-5 pt-4">
                    <div>
                        <p class="eyebrow">{{ __('products.title') }}</p>
                        <h2 class="section-title">{{ __('foodtech.detail.related') }}</h2>
                    </div>
                </div>

                <div class="grid-products">
                    @foreach ($related as $item)
                        <div>
                            @include('partials.product-card', ['product' => $item])
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
