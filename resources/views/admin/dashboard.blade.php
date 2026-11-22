@extends('layouts.lumeria')

@section('title', __('foodtech.admin.title'))

@section('content')
    @php $rp = fn ($n) => 'Rp ' . number_format($n, 0, ',', '.'); @endphp

    <section class="py-4 py-md-5">
        <div class="container">
            <h1 class="fw-bold">{{ __('foodtech.admin.title') }}</h1>
            <p class="lead text-muted">{{ __('foodtech.admin.subtitle') }}</p>

            <div class="row g-3 mb-4">
                @foreach ([
                    'stat_products'  => $stats['products'],
                    'stat_orders'    => $stats['orders'],
                    'stat_pending'   => $stats['pending'],
                    'stat_customers' => $stats['customers'],
                ] as $label => $value)
                    <div class="col-6 col-lg-3">
                        <div class="card shadow-sm h-100 border-0">
                            <div class="card-body">
                                <p class="text-muted mb-1">{{ __('foodtech.admin.' . $label) }}</p>
                                <p class="display-6 fw-bold mb-0">{{ $value }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h2 class="h4 mb-3">{{ __('foodtech.admin.latest_orders') }}</h2>
                            @forelse ($latestOrders as $order)
                                @if ($loop->first)
                                    <div class="table-responsive">
                                        <table class="table align-middle stack-table mb-0">
                                            <thead>
                                                <tr>
                                                    <th scope="col">{{ __('foodtech.history.order') }}</th>
                                                    <th scope="col">{{ __('foodtech.admin.customer') }}</th>
                                                    <th scope="col" class="text-end">{{ __('foodtech.history.total') }}</th>
                                                    <th scope="col">{{ __('foodtech.history.status') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                @endif
                                                <tr>
                                                    <td data-label="{{ __('foodtech.history.order') }}">
                                                        <a href="{{ route('order.show', $order) }}" class="fw-semibold">#{{ $order->id }}</a>
                                                    </td>
                                                    <td data-label="{{ __('foodtech.admin.customer') }}">{{ $order->customer_name }}</td>
                                                    <td data-label="{{ __('foodtech.history.total') }}" class="text-sm-end">{{ $rp($order->grand_total) }}</td>
                                                    <td data-label="{{ __('foodtech.history.status') }}">
                                                        {{ \Illuminate\Support\Facades\Lang::has('order.status.' . $order->status) ? __('order.status.' . $order->status) : $order->status }}
                                                    </td>
                                                </tr>
                                @if ($loop->last)
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            @empty
                                <p class="text-muted mb-0">{{ __('foodtech.admin.no_orders') }}</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h2 class="h4">{{ __('foodtech.admin.materials') }}</h2>
                            <p class="text-muted">{{ __('foodtech.admin.materials_text') }}</p>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('islamic') }}" class="btn btn-lumeria">{{ __('nav.islamic') }}</a>
                                <a href="{{ route('economic') }}" class="btn btn-outline-dark">{{ __('nav.economic') }}</a>
                                <a href="{{ route('team') }}" class="btn btn-outline-dark">{{ __('nav.team') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mt-4">
                <div class="card-body">
                    <h2 class="h4 mb-3">{{ __('foodtech.admin.products') }}</h2>
                    <div class="table-responsive">
                        <table class="table align-middle stack-table mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">{{ __('order.product') }}</th>
                                    <th scope="col">{{ __('foodtech.admin.category') }}</th>
                                    <th scope="col" class="text-end">{{ __('foodtech.admin.price') }}</th>
                                    <th scope="col">{{ __('foodtech.admin.badge') }}</th>
                                    <th scope="col">{{ __('foodtech.admin.active') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                        <td data-label="{{ __('order.product') }}" class="fw-semibold">
                                            <a href="{{ route('products.show', $product) }}">{{ $product->name }}</a>
                                        </td>
                                        <td data-label="{{ __('foodtech.admin.category') }}">{{ __('products.categories.' . $product->category) }}</td>
                                        <td data-label="{{ __('foodtech.admin.price') }}" class="text-sm-end">{{ $product->formatted_price }}</td>
                                        <td data-label="{{ __('foodtech.admin.badge') }}">{{ $product->badge_label ?? '-' }}</td>
                                        <td data-label="{{ __('foodtech.admin.active') }}">{{ $product->is_active ? __('foodtech.admin.yes') : __('foodtech.admin.no') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
