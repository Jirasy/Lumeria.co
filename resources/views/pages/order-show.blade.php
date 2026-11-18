@extends('layouts.lumeria')

@section('title', __('order.detail_title', ['id' => $order->id]))

@section('content')
    @php
        $rp = fn ($n) => 'Rp ' . number_format($n, 0, ',', '.');
        $isPaylater = $order->payment_method === 'paylater';
        $rateText = $order->margin_rate !== null
            ? rtrim(rtrim(number_format($order->margin_rate, 2, '.', ''), '0'), '.')
            : null;
    @endphp

    <section class="py-5">
        <div class="container" style="max-width: 52rem">
            <h1 class="fw-bold">{{ __('order.detail_title', ['id' => $order->id]) }}</h1>
            <p>
                {{ __('order.status_label') }}:
                <span class="badge text-bg-warning">{{ __('order.status.' . $order->status) }}</span>
            </p>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h5">{{ __('order.customer') }}</h2>
                    <p class="mb-1">{{ $order->customer_name }}</p>
                    <p class="mb-1">{{ $order->customer_phone }}</p>
                    @if ($order->customer_address)
                        <p class="mb-0">{!! nl2br(e($order->customer_address)) !!}</p>
                    @endif
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h5">{{ __('order.items') }}</h2>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
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
                                        <td>{{ $item->product_name }}</td>
                                        <td class="text-end">{{ $rp($item->price) }}</td>
                                        <td class="text-end">{{ $item->quantity }}</td>
                                        <td class="text-end">{{ $rp($item->line_total) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">{{ __('order.subtotal') }}</th>
                                    <th class="text-end">{{ $rp($order->subtotal) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h5">{{ __('order.payment') }}</h2>
                    <dl class="row mb-0">
                        <dt class="col-sm-5">{{ __('order.payment_method') }}</dt>
                        <dd class="col-sm-7">{{ __('order.methods.' . $order->payment_method) }}</dd>

                        @if ($isPaylater)
                            <dt class="col-sm-5">{{ __('order.tenor') }}</dt>
                            <dd class="col-sm-7">{{ $order->tenor_months }} {{ __('order.months') }}</dd>

                            <dt class="col-sm-5">{{ __('order.margin_rate') }}</dt>
                            <dd class="col-sm-7">{{ __('order.per_month', ['rate' => $rateText]) }}</dd>

                            <dt class="col-sm-5">{{ __('order.margin') }}</dt>
                            <dd class="col-sm-7">{{ $rp($order->margin_total) }}</dd>

                            <dt class="col-sm-5">{{ __('order.installment') }}</dt>
                            <dd class="col-sm-7 fw-semibold">{{ $rp($order->installment_amount) }}</dd>
                        @endif

                        <dt class="col-sm-5">{{ __('order.total_pay') }}</dt>
                        <dd class="col-sm-7 fw-bold">{{ $rp($order->grand_total) }}</dd>
                    </dl>

                    @if ($isPaylater)
                        <h3 class="h6 mt-4">{{ __('order.schedule') }}</h3>
                        <ul class="list-group">
                            @for ($n = 1; $n <= $order->tenor_months; $n++)
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>{{ __('order.installment_n', ['n' => $n]) }}</span>
                                    <span>{{ $rp($order->installment_amount) }}</span>
                                </li>
                            @endfor
                        </ul>
                    @endif
                </div>
            </div>

            <p class="small text-muted">{{ __('order.privacy_note') }}</p>
            <a href="{{ route('products') }}" class="btn btn-lumeria">{{ __('order.order_again') }}</a>
        </div>
    </section>
@endsection