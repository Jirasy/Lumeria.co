@extends('layouts.lumeria')


@section('title', __('home.title'))


@section('content')
    <section class="hero-section py-5">
        <div class="container py-lg-4">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <h1 class="display-4 fw-bold">{{ __('common.brand') }}</h1>
                    <p class="lead">{{ __('home.hero_identity') }}</p>
                    <p class="fs-5 fst-italic text-lumeria">&ldquo;{{ __('home.tagline') }}&rdquo;</p>
                    <div class="d-flex flex-wrap gap-2 mt-4">
                        <a href="{{ route('products') }}" class="btn btn-lumeria btn-lg">{{ __('home.cta_products') }}</a>
                        <a href="{{ route('order') }}" class="btn btn-outline-dark btn-lg">{{ __('home.cta_order') }}</a>
                    </div>
                </div>
                    <div class="col-lg-5 mt-4 mt-lg-0" aria-hidden="true">
                        <div class="hero-art">
                            <div class="hero-blob"></div>
                                <x-product-art slug="original-milk" :bare="true" :size="170" class="hero-ball hero-ball-1" />
                                <x-product-art slug="bola-teh" :bare="true" :size="130" class="hero-ball hero-ball-2" />
                                <x-product-art slug="bola-kopi" :bare="true" :size="150" class="hero-ball hero-ball-3" />
                            </div>
                    </div>
            </div>
        </div>
    </section>


    <section class="py-5">
        <div class="container">
            <h2 class="h1 fw-bold mb-1">{{ __('home.signature_title') }}</h2>
            <p class="text-muted mb-4">{{ __('home.signature_sub') }}</p>
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
    <section class="pb-5">
        <div class="container">
            @include('partials.catering-card')
        </div>
    </section>


    {{-- Carousel semua produk --}}
    <section class="pb-5">
        <div class="container">
            @include('partials.product-carousel', ['products' => $catalog, 'id' => 'home-menu'])
        </div>
    </section>


    <section class="py-5 bg-white">
        <div class="container">
            <h2 class="h1 fw-bold mb-3">{{ __('home.about_title') }}</h2>
            <p class="lead mb-4">{{ __('home.about_text') }}</p>


            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <h3 class="h4 text-lumeria">{{ __('home.vision_title') }}</h3>
                            <p class="mb-0">{{ __('home.vision_text') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <h3 class="h4 text-lumeria">{{ __('home.mission_title') }}</h3>
                            <ul class="mb-0 ps-3">
                                @foreach ((array) __('home.mission_items') as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="py-5 bg-lumeria">
        <div class="container text-center">
            <h2 class="display-6 fw-bold">{{ __('home.philosophy_title') }}</h2>
            <p class="lead mx-auto mb-0" style="max-width: 40rem">{{ __('home.philosophy_text') }}</p>
        </div>
    </section>
@endsection
