@extends('layouts.lumeria')

@section('title', __('foodtech.history.title'))

@section('content')
    @php $rp = fn ($n) => 'Rp ' . number_format($n, 0, ',', '.'); @endphp

    <section class="order-page">
        <div class="container">
            <header class="page-head">
                <p class="page-eyebrow">{{ __('foodtech.nav.my_orders') }}</p>
                <div class="page-head-row">
                    <div>
                        <h1 class="page-title">{{ __('foodtech.history.title') }}</h1>
                        <p class="page-sub">{{ __('foodtech.history.subtitle') }}</p>
                    </div>
                    @unless ($orders->isEmpty())
                        <span class="order-chip">{{ trans_choice('foodtech.history.count', $orders->total()) }}</span>
                    @endunless
                </div>
            </header>

            @if ($orders->isEmpty())
                <div class="order-card">
                    <div class="order-card-body">
                        <div class="order-empty">
                            <x-product-art slug="bola-teh" :size="96" />
                            <h2 class="order-empty-title">{{ __('foodtech.history.empty') }}</h2>
                            <p class="text-muted mb-3">{{ __('order.empty_text') }}</p>
                            <a href="{{ route('products') }}" class="btn btn-lumeria">{{ __('order.browse') }}</a>
                        </div>
                    </div>
                </div>
            @else
                <div class="order-card">
                    <div class="table-responsive">
                        <table class="table align-middle stack-table order-history-table mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">{{ __('foodtech.history.order') }}</th>
                                    <th scope="col">{{ __('foodtech.history.date') }}</th>
                                    <th scope="col" class="text-end">{{ __('foodtech.history.items') }}</th>
                                    <th scope="col">{{ __('foodtech.history.payment') }}</th>
                                    <th scope="col" class="text-end">{{ __('foodtech.history.total') }}</th>
                                    <th scope="col">{{ __('foodtech.history.status') }}</th>
                                    <th scope="col"><span class="visually-hidden">{{ __('foodtech.history.view') }}</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td data-label="{{ __('foodtech.history.order') }}" class="fw-semibold">#{{ $order->id }}</td>
                                        <td data-label="{{ __('foodtech.history.date') }}">{{ $order->created_at->translatedFormat('j M Y') }}</td>
                                        <td data-label="{{ __('foodtech.history.items') }}" class="text-sm-end">{{ $order->items_count }}</td>
                                        <td data-label="{{ __('foodtech.history.payment') }}">{{ __('order.methods.' . $order->payment_method) }}</td>
                                        <td data-label="{{ __('foodtech.history.total') }}" class="text-sm-end fw-semibold">{{ $rp($order->grand_total) }}</td>
                                        <td data-label="{{ __('foodtech.history.status') }}">
                                            <span class="order-status" data-status="{{ $order->status }}">
                                                {{ \Illuminate\Support\Facades\Lang::has('order.status.' . $order->status) ? __('order.status.' . $order->status) : $order->status }}
                                            </span>
                                        </td>
                                        <td data-label="" class="text-sm-end">
                                            <a href="{{ route('order.show', $order) }}" class="btn btn-sm btn-outline-dark"
                                               aria-label="{{ __('foodtech.history.view') }} {{ __('foodtech.history.order') }} #{{ $order->id }}">{{ __('foodtech.history.view') }}</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-3">{{ $orders->links('pagination::bootstrap-5') }}</div>
            @endif
        </div>
    </section>
@endsection
