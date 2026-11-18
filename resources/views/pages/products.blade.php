@extends('layouts.lumeria')

@section('title', __('products.title'))

@section('content')
    <section class="py-5">
        <div class="container">
            <h1 class="fw-bold">{{ __('products.title') }}</h1>
            <p class="lead text-muted">{{ __('products.subtitle') }}</p>

            @foreach ([['bola_susu', $signature], ['lumer_lainnya', $others]] as [$key, $list])
                <h2 class="h3 fw-bold mt-5 mb-3">{{ __('products.categories.' . $key) }}</h2>
                <div class="row g-4">
                    @forelse ($list as $product)
                        <div class="col-12 col-md-6 col-lg-4">
                            @include('partials.product-card', ['product' => $product])
                        </div>
                    @empty
                        <p class="text-muted">{{ __('products.empty') }}</p>
                    @endforelse
                </div>
            @endforeach
        </div>
    </section>
@endsection