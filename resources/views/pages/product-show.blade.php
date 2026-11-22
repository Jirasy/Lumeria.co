@extends('layouts.lumeria')

@section('title', $product->name)

@section('content')
    @php
        $wa = \App\Support\StoreContact::whatsapp(__('foodtech.detail.wa_message', ['product' => $product->name]));
    @endphp

    <section class="py-4 py-md-5">
        <div class="container">
            <a href="{{ route('products') }}" class="d-inline-block mb-3">{{ __('foodtech.detail.back') }}</a>

            {{-- id="product-{id}" dipakai CartController untuk kembali ke posisi ini setelah "Tambah ke Keranjang" --}}
            <div id="product-{{ $product->id }}" class="row g-4 g-lg-5 align-items-center">
                <div class="col-md-6">
                    <div class="ft-media ft-detail-media">
                        @if ($product->badge_label)
                            <span class="ft-badge ft-badge-{{ $product->badge_tone }}">{{ $product->badge_label }}</span>
                        @endif
                        @if ($product->image)
                            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-100 h-100 object-fit-cover">
                        @else
                            <x-product-art :slug="$product->slug" :size="220" />
                        @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <p class="text-muted mb-1">{{ __('products.categories.' . $product->category) }}</p>
                    <h1 class="fw-bold d-flex flex-wrap align-items-center gap-2">
                        {{ $product->name }}
                        @if ($product->is_signature)
                            <span class="badge rounded-pill badge-lumeria fs-6">{{ __('products.signature_badge') }}</span>
                        @endif
                    </h1>
                    <p class="display-6 fw-bold my-3">{{ $product->formatted_price }}</p>
                    <p class="lead">{{ $product->description }}</p>

                    @auth
                        <form method="POST" action="{{ route('cart.add') }}" class="d-flex gap-2 mt-4" style="max-width: 24rem">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <label for="qty-{{ $product->id }}" class="visually-hidden">{{ __('products.qty') }}</label>
                            <input id="qty-{{ $product->id }}" type="number" name="quantity" value="1" min="1" max="99"
                                   class="form-control" style="max-width: 5.5rem">
                            <button type="submit" class="btn btn-lumeria btn-lg flex-grow-1">{{ __('products.add') }}</button>
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

                    <div class="d-flex flex-wrap gap-3 mt-4">
                        @if ($wa)
                            <a href="{{ $wa }}" target="_blank" rel="noopener noreferrer" class="d-inline-flex align-items-center gap-2 fw-semibold">
                                <x-icon name="whatsapp" :size="18" />{{ __('foodtech.detail.order_wa') }}
                            </a>
                        @endif
                        <a href="{{ route('order', ['amount' => $product->price]) }}" class="fw-semibold">{{ __('foodtech.detail.paylater') }}</a>
                    </div>
                </div>
            </div>

            @if ($related->isNotEmpty())
                <h2 class="h3 fw-bold mt-5 mb-3">{{ __('foodtech.detail.related') }}</h2>
                <div class="row g-4">
                    @foreach ($related as $item)
                        <div class="col-12 col-md-6 col-lg-4">
                            @include('partials.product-card', ['product' => $item])
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
