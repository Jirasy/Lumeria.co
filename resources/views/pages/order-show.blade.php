@extends('layouts.lumeria')

@section('title', __('order.detail_title', ['id' => $order->id]))

@section('content')
    @php
        $rp = fn ($n) => 'Rp ' . number_format($n, 0, ',', '.');
        $isPaylater = $order->payment_method === 'paylater';
        $rateText = $order->margin_rate !== null
            ? rtrim(rtrim(number_format($order->margin_rate, 2, '.', ''), '0'), '.')
            : null;
        $itemCount = (int) $order->items->sum('quantity');
    @endphp

    <section class="order-page">
        <div class="container order-detail">
            <header class="page-head">
                <a href="{{ route('orders.index') }}" class="page-back">
                    <x-icon name="chevron-left" :size="14" />{{ __('foodtech.history.title') }}
                </a>
                <div class="page-head-row">
                    <div>
                        <h1 class="page-title">{{ __('order.detail_title', ['id' => $order->id]) }}</h1>
                        <p class="page-sub">{{ __('order.detail_sub') }}</p>
                    </div>
                    <span class="order-status" data-status="{{ $order->status }}">
                        {{ \Illuminate\Support\Facades\Lang::has('order.status.' . $order->status) ? __('order.status.' . $order->status) : $order->status }}
                    </span>
                </div>
                <p class="order-detail-date">{{ __('order.placed_on', ['date' => $order->created_at->translatedFormat('j F Y, H:i')]) }}</p>
            </header>

            <div class="order-detail-grid">
                <div class="order-detail-fact">
                    <span>{{ __('order.total_pay') }}</span>
                    <strong>{{ $rp($order->grand_total) }}</strong>
                </div>
                <div class="order-detail-fact">
                    <span>{{ __('order.payment_method') }}</span>
                    <strong>{{ __('order.methods.' . $order->payment_method) }}</strong>
                </div>
                <div class="order-detail-fact">
                    <span>{{ __('order.items') }}</span>
                    <strong>{{ $itemCount }}</strong>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="order-card">
                        <div class="order-card-head">
                            <h2 class="order-card-title">{{ __('order.items') }}</h2>
                        </div>
                        <div class="order-card-body">
                            <div class="table-responsive">
                                <table class="table align-middle stack-table mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col">{{ __('order.product') }}</th>
                                            <th scope="col" class="text-end">{{ __('order.price') }}</th>
                                            <th scope="col" class="text-end">{{ __('order.qty') }}</th>
                                            <th scope="col" class="text-end">{{ __('order.line_total') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order->items as $item)
                                            <tr>
                                                <td data-label="{{ __('order.product') }}" class="fw-semibold">{{ $item->product_name }}</td>
                                                <td data-label="{{ __('order.price') }}" class="text-sm-end">{{ $rp($item->price) }}</td>
                                                <td data-label="{{ __('order.qty') }}" class="text-sm-end">{{ $item->quantity }}</td>
                                                <td data-label="{{ __('order.line_total') }}" class="text-sm-end">{{ $rp($item->line_total) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" class="text-sm-end">{{ __('order.subtotal') }}</th>
                                            <th class="text-sm-end">{{ $rp($order->subtotal) }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="order-card">
                        <div class="order-card-head">
                            <h2 class="order-card-title">{{ __('order.payment') }}</h2>
                        </div>
                        <div class="order-card-body">
                            <dl class="order-summary-rows">
                                <div class="order-summary-row">
                                    <dt>{{ __('order.payment_method') }}</dt>
                                    <dd>{{ __('order.methods.' . $order->payment_method) }}</dd>
                                </div>

                                @if ($isPaylater)
                                    <div class="order-summary-row">
                                        <dt>{{ __('order.tenor') }}</dt>
                                        <dd>{{ $order->tenor_months }} {{ __('order.months') }}</dd>
                                    </div>
                                    <div class="order-summary-row">
                                        <dt>{{ __('order.margin_rate') }}</dt>
                                        <dd>{{ __('order.per_month', ['rate' => $rateText]) }}</dd>
                                    </div>
                                    <div class="order-summary-row">
                                        <dt>{{ __('order.margin') }}</dt>
                                        <dd>{{ $rp($order->margin_total) }}</dd>
                                    </div>
                                    <div class="order-summary-row">
                                        <dt>{{ __('order.installment') }}</dt>
                                        <dd>{{ $rp($order->installment_amount) }}</dd>
                                    </div>
                                @endif

                                <div class="order-summary-row order-summary-total">
                                    <dt>{{ __('order.total_pay') }}</dt>
                                    <dd>{{ $rp($order->grand_total) }}</dd>
                                </div>
                            </dl>

                            @if ($isPaylater)
                                <h3 class="h6 mt-4">{{ __('order.schedule') }}</h3>
                                <ul class="order-schedule">
                                    @for ($n = 1; $n <= $order->tenor_months; $n++)
                                        <li>
                                            <span>{{ __('order.installment_n', ['n' => $n]) }}</span>
                                            <span>{{ $rp($order->installment_amount) }}</span>
                                        </li>
                                    @endfor
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="order-aside">
                        <div class="order-card">
                            <div class="order-card-head">
                                <h2 class="order-card-title">{{ __('order.customer') }}</h2>
                            </div>
                            <div class="order-card-body">
                                <p class="fw-semibold mb-1">{{ $order->customer_name }}</p>
                                <p class="mb-1 text-muted">{{ $order->customer_phone }}</p>
                                @if ($order->customer_address)
                                    <p class="mb-0 text-muted">{!! nl2br(e($order->customer_address)) !!}</p>
                                @endif
                            </div>
                        </div>

                        <div class="order-card">
                            <div class="order-card-body">
                                <p class="small text-muted mb-3">{{ __('order.privacy_note') }}</p>
                                <a href="{{ route('products') }}" class="btn btn-lumeria w-100">{{ __('order.order_again') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
