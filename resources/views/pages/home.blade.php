@extends('layouts.lumeria')

@section('title', __('home.title'))
@section('description', __('home.hero_identity') . ' ' . __('home.tagline') . '.')

@section('content')
    {{-- Hero --}}
    <section class="hero">
        <div class="shell hero-grid">
            <div class="hero-copy">
                <span class="hero-kicker">
                    <span class="dot" aria-hidden="true"></span>{{ __('home.eyebrow') }}
                </span>

                <h1 class="hero-title">{{ __('home.hero_title') }}</h1>

                <p class="hero-text">{{ __('home.hero_identity') }}</p>
                <p class="hero-tag">{{ __('home.tagline') }}</p>

                <div class="hero-actions">
                    <a href="{{ route('products') }}" class="btn btn-lumeria btn-lg">{{ __('home.cta_products') }}</a>
                    <a href="{{ route('order') }}" class="btn btn-outline-dark btn-lg">{{ __('home.cta_order') }}</a>
                </div>

                <ul class="hero-points">
                    <li><x-icon name="check" :size="16" />{{ __('home.hero_point_1') }}</li>
                    <li><x-icon name="check" :size="16" />{{ __('home.hero_point_2') }}</li>
                    <li><x-icon name="check" :size="16" />{{ __('home.hero_point_3') }}</li>
                </ul>
            </div>

            <div class="hero-media">
                <div class="hero-frame">
                    <img src="{{ asset('images/hero-lumeria.jpg') }}"
                         alt="{{ __('home.hero_caption_title') }} {{ __('common.brand') }}"
                         width="880" height="1184" fetchpriority="high">
                </div>

                <div class="hero-chip">
                    <strong>{{ __('home.hero_caption_title') }}</strong>
                    {{ __('home.hero_caption') }}
                </div>

                <x-product-art slug="original-milk" :bare="true" :size="150" class="hero-ball hero-ball-1" />
                <x-product-art slug="bola-teh" :bare="true" :size="130" class="hero-ball hero-ball-2" />
                <x-product-art slug="bola-kopi" :bare="true" :size="140" class="hero-ball hero-ball-3" />
            </div>
        </div>
    </section>

    {{-- Ticker --}}
    <div class="ticker" aria-hidden="true">
        <div class="ticker-track">
            @for ($copy = 0; $copy < 2; $copy++)
                <div class="ticker-group">
                    @foreach ((array) __('home.marquee') as $item)
                        <span class="ticker-item">{{ $item }}</span>
                    @endforeach
                </div>
            @endfor
        </div>
    </div>

    {{-- Menu pilihan --}}
    <section class="section">
        <div class="shell">
            <div class="section-head">
                <div>
                    <p class="eyebrow">{{ __('home.favorites_eyebrow') }}</p>
                    <h2 class="section-title">{{ __('home.favorites_title') }}</h2>
                    <p class="section-sub">{{ __('home.favorites_sub') }}</p>
                </div>
                <a href="{{ route('products') }}" class="link-arrow">
                    {{ __('home.view_all') }}<x-icon name="arrow-right" :size="15" />
                </a>
            </div>

            <div class="grid-3">
                @forelse ($featured as $product)
                    @include('partials.featured-card', ['product' => $product])
                @empty
                    <p class="text-muted">{{ __('products.empty') }}</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Kategori --}}
    <section class="section section--soft">
        <div class="shell">
            <div class="section-head">
                <div>
                    <p class="eyebrow">{{ __('home.categories_eyebrow') }}</p>
                    <h2 class="section-title">{{ __('home.categories_title') }}</h2>
                    <p class="section-sub">{{ __('home.categories_sub') }}</p>
                </div>
            </div>

            <div class="cat-grid">
                <a href="{{ route('products') }}#bola-susu" class="cat-card">
                    <img src="{{ asset('images/products/original-milk.jpg') }}" alt="" loading="lazy" width="1024" height="1024">
                    <div class="cat-card-body">
                        <span class="cat-card-label">01</span>
                        <h3 class="cat-card-title">{{ __('home.cat_bola_title') }}</h3>
                        <p class="cat-card-text">{{ __('home.cat_bola_text') }}</p>
                        <span class="cat-card-cta">{{ __('home.cat_cta') }}<x-icon name="arrow-right" :size="14" /></span>
                    </div>
                </a>

                <a href="{{ route('products') }}#lumer-lainnya" class="cat-card">
                    <img src="{{ asset('images/products/bolu-lumer-cokelat-keju.jpg') }}" alt="" loading="lazy" width="1024" height="1024">
                    <div class="cat-card-body">
                        <span class="cat-card-label">02</span>
                        <h3 class="cat-card-title">{{ __('home.cat_lumer_title') }}</h3>
                        <p class="cat-card-text">{{ __('home.cat_lumer_text') }}</p>
                        <span class="cat-card-cta">{{ __('home.cat_cta') }}<x-icon name="arrow-right" :size="14" /></span>
                    </div>
                </a>

                <a href="{{ route('order') }}" class="cat-card">
                    <img src="{{ asset('images/catering-lumeria.jpg') }}" alt="" loading="lazy" width="1392" height="752">
                    <div class="cat-card-body">
                        <span class="cat-card-label">03</span>
                        <h3 class="cat-card-title">{{ __('home.cat_catering_title') }}</h3>
                        <p class="cat-card-text">{{ __('home.cat_catering_text') }}</p>
                        <span class="cat-card-cta">{{ __('home.cat_cta') }}<x-icon name="arrow-right" :size="14" /></span>
                    </div>
                </a>
            </div>
        </div>
    </section>

    {{-- Produk signature --}}
    <section class="section">
        <div class="shell">
            <div class="section-head">
                <div>
                    <p class="eyebrow">{{ __('products.signature_badge') }}</p>
                    <h2 class="section-title">{{ __('home.signature_title') }}</h2>
                    <p class="section-sub">{{ __('home.signature_sub') }}</p>
                </div>
                <a href="{{ route('products') }}" class="link-arrow">
                    {{ __('home.view_all') }}<x-icon name="arrow-right" :size="15" />
                </a>
            </div>

            <div class="grid-products">
                @forelse ($signatureProducts as $product)
                    <div>
                        @include('partials.product-card', ['product' => $product])
                    </div>
                @empty
                    <p class="text-muted">{{ __('products.empty') }}</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Cerita merek --}}
    <section class="section section--plain">
        <div class="shell story-grid">
            <div class="story-media">
                <img src="{{ asset('images/kitchen-lumeria.jpg') }}"
                     alt="{{ __('home.story_title') }}" loading="lazy" width="880" height="1184">
            </div>

            <div>
                <p class="eyebrow">{{ __('home.story_eyebrow') }}</p>
                <h2 class="section-title">{{ __('home.story_title') }}</h2>
                <p class="section-sub">{{ __('home.about_text') }}</p>

                <blockquote class="story-quote">
                    &ldquo;{{ __('home.philosophy_text') }}&rdquo;
                    <footer>{{ __('home.philosophy_title') }}</footer>
                </blockquote>

                <p class="mt-4 mb-0">
                    <a href="{{ route('products') }}" class="link-arrow">
                        {{ __('home.story_cta') }}<x-icon name="arrow-right" :size="15" />
                    </a>
                </p>
            </div>
        </div>
    </section>

    {{-- Pilar --}}
    <section class="section section--soft">
        <div class="shell">
            <div class="section-head">
                <div>
                    <p class="eyebrow">{{ __('home.benefits_eyebrow') }}</p>
                    <h2 class="section-title">{{ __('home.benefits_title') }}</h2>
                </div>
            </div>

            <div class="pillars">
                @foreach ((array) __('home.benefits') as $index => $benefit)
                    <article class="pillar">
                        <span class="pillar-num">0{{ $index + 1 }}</span>
                        <h3>{{ $benefit['title'] }}</h3>
                        <p>{{ $benefit['text'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Cara pesan --}}
    <section class="section">
        <div class="shell">
            <div class="section-head">
                <div>
                    <p class="eyebrow">{{ __('home.steps_eyebrow') }}</p>
                    <h2 class="section-title">{{ __('home.steps_title') }}</h2>
                </div>
            </div>

            <div class="steps">
                @foreach ((array) __('home.steps') as $index => $step)
                    <article class="step">
                        <span class="step-num">0{{ $index + 1 }}</span>
                        <h3>{{ $step['title'] }}</h3>
                        <p>{{ $step['text'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Carousel semua produk --}}
    <section class="section section--tight">
        <div class="shell">
            @include('partials.product-carousel', ['products' => $catalog, 'id' => 'home-menu'])
        </div>
    </section>

    {{-- Event & Catering --}}
    <section class="section section--soft">
        <div class="shell">
            @include('partials.catering-card')
        </div>
    </section>

    {{-- FAQ --}}
    <section class="section" id="faq">
        <div class="shell">
            <div class="row g-4 g-lg-5">
                <div class="col-lg-4">
                    <p class="eyebrow">{{ __('home.faq_eyebrow') }}</p>
                    <h2 class="section-title">{{ __('home.faq_title') }}</h2>
                </div>

                <div class="col-lg-8">
                    <div class="faq-list">
                        @foreach ((array) __('home.faq') as $item)
                            <details class="faq-item" name="lumeria-faq">
                                <summary>
                                    {{ $item['q'] }}
                                    <span class="faq-mark" aria-hidden="true"></span>
                                </summary>
                                <p class="faq-answer">{{ $item['a'] }}</p>
                            </details>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Galeri --}}
    <section class="section section--plain">
        <div class="shell">
            <div class="section-head">
                <div>
                    <p class="eyebrow">{{ __('home.gallery_eyebrow') }}</p>
                    <h2 class="section-title">{{ __('home.gallery_title') }}</h2>
                </div>

                @php $ig = \App\Support\StoreContact::instagram(); @endphp
                @if ($ig)
                    <a href="{{ $ig['url'] }}" target="_blank" rel="noopener noreferrer" class="link-arrow">
                        {{ __('home.gallery_cta') }}<x-icon name="arrow-right" :size="15" />
                    </a>
                @endif
            </div>

            <div class="gallery-grid">
                <div class="gallery-item">
                    <img src="{{ asset('images/kitchen-lumeria.jpg') }}" alt="{{ __('home.story_title') }}" loading="lazy" width="880" height="1184">
                </div>
                <div class="gallery-item">
                    <img src="{{ asset('images/catering-lumeria.jpg') }}" alt="{{ __('home.cat_catering_title') }}" loading="lazy" width="1392" height="752">
                </div>
                <div class="gallery-item">
                    <img src="{{ asset('images/products/bolu-lumer-cokelat-keju.jpg') }}" alt="{{ __('foodtech.catering.label') }}" loading="lazy" width="1024" height="1024">
                </div>
                <div class="gallery-item">
                    <img src="{{ asset('images/products/bola-kopi.jpg') }}" alt="{{ __('home.cat_bola_title') }}" loading="lazy" width="1024" height="1024">
                </div>
                <div class="gallery-item">
                    <img src="{{ asset('images/products/es-krim-lumeria-creamy.jpg') }}" alt="{{ __('home.cat_es_title') }}" loading="lazy" width="1024" height="1024">
                </div>
            </div>
        </div>
    </section>

    {{-- Ajakan terakhir --}}
    <section class="section section--tight">
        <div class="shell">
            <div class="cta-band">
                <p class="eyebrow eyebrow--plain">{{ __('home.tagline') }}</p>
                <h2 class="section-title">{{ __('home.cta_title') }}</h2>
                <p>{{ __('home.cta_text') }}</p>

                <div class="cta-actions">
                    <a href="{{ route('products') }}" class="btn btn-accent btn-lg">{{ __('home.cta_products') }}</a>
                    <a href="{{ route('order') }}" class="btn btn-outline-dark btn-lg">{{ __('home.cta_order') }}</a>
                </div>
            </div>
        </div>
    </section>
@endsection
