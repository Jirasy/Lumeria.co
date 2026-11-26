@extends('layouts.lumeria')


@section('title', __('home.title'))


@section('content')
    @php
        $featured = $signatureProducts->first();
        $tenors   = config('lumeria.paylater.tenors', [1, 2, 3]);
    @endphp


    <section class="home-hero">
        <div class="container">
            <div class="row align-items-center g-4 g-lg-5">
                <div class="col-lg-6">
                    <p class="home-eyebrow">{{ __('home.hero_eyebrow') }}</p>
                    <h1 class="home-hero-title">{{ __('common.brand') }}</h1>
                    <p class="home-hero-lead">{{ __('home.hero_identity') }}</p>
                    <p class="home-hero-tagline">&ldquo;{{ __('home.tagline') }}&rdquo;</p>
                    <div class="d-flex flex-wrap gap-2 mt-4">
                        <a href="{{ route('products') }}" class="btn btn-lumeria btn-lg">{{ __('home.cta_products') }}</a>
                        <a href="{{ route('order') }}" class="btn btn-outline-dark btn-lg">{{ __('home.cta_order') }}</a>
                    </div>
                    <ul class="home-points">
                        @foreach ((array) __('home.hero_points') as $point)
                            <li>
                                <span class="home-point-mark" aria-hidden="true"><x-icon name="check" :size="12" /></span>
                                {{ $point }}
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="col-lg-6">
                    <div class="hero-art">
                        <div class="hero-blob" aria-hidden="true"></div>
                        <x-product-art slug="original-milk" :bare="true" :size="190" class="hero-ball hero-ball-1" />
                        <x-product-art slug="bola-teh" :bare="true" :size="140" class="hero-ball hero-ball-2" />
                        <x-product-art slug="bola-kopi" :bare="true" :size="165" class="hero-ball hero-ball-3" />

                        @if ($featured)
                            <a href="{{ route('products.show', $featured) }}" class="home-hero-card">
                                <span class="home-hero-card-label">{{ __('products.signature_badge') }}</span>
                                <span class="home-hero-card-name">{{ $featured->display_name }}</span>
                                <span class="home-hero-card-price">{{ $featured->formatted_price }}</span>
                                <span class="home-hero-card-go" aria-hidden="true"><x-icon name="arrow-right" :size="16" /></span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <ul class="home-facts">
                <li>
                    <strong>{{ $catalog->count() }}</strong>
                    <span>{{ __('home.fact_products') }}</span>
                </li>
                <li>
                    <strong>{{ $signatureProducts->count() }}</strong>
                    <span>{{ __('home.fact_signature') }}</span>
                </li>
                <li>
                    <strong>{{ min($tenors) }}&ndash;{{ max($tenors) }}</strong>
                    <span>{{ __('home.fact_tenor') }}</span>
                </li>
            </ul>
        </div>
    </section>


    <section class="home-section">
        <div class="container">
            <div class="home-section-head">
                <div>
                    <p class="home-eyebrow">{{ __('home.menu_eyebrow') }}</p>
                    <h2 class="home-section-title">{{ __('home.signature_title') }}</h2>
                    <p class="home-section-sub">{{ __('home.signature_sub') }}</p>
                </div>
                <a href="{{ route('products') }}" class="home-section-link">
                    {{ __('foodtech.carousel.all') }}<x-icon name="arrow-right" :size="16" />
                </a>
            </div>

            <div class="row g-4">
                @forelse ($signatureProducts as $product)
                    <div class="col-12 col-md-6 col-lg-4">
                        @include('partials.product-card', ['product' => $product])
                    </div>
                @empty
                    <p class="text-muted">{{ __('products.empty') }}</p>
                @endforelse
            </div>
        </div>
    </section>


    {{-- Event & Catering --}}
    <section class="home-section">
        <div class="container">
            @include('partials.catering-card')
        </div>
    </section>


    {{-- Carousel semua produk --}}
    <section class="home-section">
        <div class="container">
            @include('partials.product-carousel', ['products' => $catalog, 'id' => 'home-menu'])
        </div>
    </section>


    <section class="home-about">
        <div class="container">
            <div class="row g-4 g-lg-5 align-items-start">
                <div class="col-lg-5">
                    <p class="home-eyebrow">{{ __('home.about_eyebrow') }}</p>
                    <h2 class="home-section-title">{{ __('home.about_title') }}</h2>
                    <p class="home-about-text">{{ __('home.about_text') }}</p>
                    <a href="{{ route('products') }}" class="btn btn-outline-dark mt-2">{{ __('home.about_link') }}</a>
                </div>

                <div class="col-lg-7">
                    <div class="row g-3 g-lg-4">
                        <div class="col-md-5">
                            <article class="home-value-card home-value-card-accent h-100">
                                <h3 class="home-value-title">{{ __('home.vision_title') }}</h3>
                                <p class="mb-0 text-muted">{{ __('home.vision_text') }}</p>
                            </article>
                        </div>
                        <div class="col-md-7">
                            <article class="home-value-card h-100">
                                <h3 class="home-value-title">{{ __('home.mission_title') }}</h3>
                                <ol class="home-mission">
                                    @foreach ((array) __('home.mission_items') as $item)
                                        <li>{{ $item }}</li>
                                    @endforeach
                                </ol>
                            </article>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="home-philosophy">
        <div class="container">
            <div class="home-philosophy-inner">
                <img src="{{ asset('images/lumeria-logo.png') }}" alt="" width="56" height="56"
                     class="home-philosophy-mark" loading="lazy">
                <p class="home-eyebrow home-eyebrow-light">{{ __('home.philosophy_eyebrow') }}</p>
                <h2 class="home-philosophy-title">{{ __('home.philosophy_title') }}</h2>
                <p class="home-philosophy-text">{{ __('home.philosophy_text') }}</p>
            </div>
        </div>
    </section>
@endsection
