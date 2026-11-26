@extends('layouts.lumeria')

@section('title', __('products.title'))
@section('description', __('products.subtitle'))

@section('content')
    <section class="section section--tight">
        <div class="shell">
            <p class="eyebrow">{{ __('nav.products') }}</p>
            <h1 class="page-title">{{ __('products.title') }}</h1>
            <p class="page-sub">{{ __('products.subtitle') }}</p>
        </div>
    </section>

    @foreach ([['bola_susu', $signature, 'bola-susu'], ['lumer_lainnya', $others, 'lumer-lainnya']] as [$key, $list, $anchor])
        <section class="section section--tight" id="{{ $anchor }}">
            <div class="shell">
                <div class="section-head">
                    <div>
                        <p class="eyebrow">0{{ $loop->iteration }}</p>
                        <h2 class="section-title">{{ __('products.categories.' . $key) }}</h2>
                    </div>
                </div>

                <div class="grid-products">
                    @forelse ($list as $product)
                        <div>
                            @include('partials.product-card', ['product' => $product])
                        </div>
                    @empty
                        <p class="text-muted">{{ __('products.empty') }}</p>
                    @endforelse
                </div>
            </div>
        </section>
    @endforeach

    <section class="section section--tight">
        <div class="shell">
            @include('partials.catering-card')
        </div>
    </section>
@endsection
