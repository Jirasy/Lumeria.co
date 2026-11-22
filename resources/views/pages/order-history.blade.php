@extends('layouts.lumeria')

@section('title', __('foodtech.history.title'))

@section('content')
    @php $rp = fn ($n) => 'Rp ' . number_format($n, 0, ',', '.'); @endphp

    <section class="py-4 py-md-5">
        <div class="container">
            <h1 class="fw-bold">{{ __('foodtech.history.title') }}</h1>
            <p class="lead text-muted">{{ __('foodtech.history.subtitle') }}</p>

            @if ($orders->isEmpty())
                <div class="card shadow-sm">
                    <div class="card-body">
                        <p class="text-muted">{{ __('foodtech.history.empty') }}</p>
                        <a href="{{ route('products') }}" class="btn btn-lumeria">{{ __('order.browse') }}</a>
                    </div>
                </div>
            @else
                <div class="card shadow-sm">
                    <div class="table-responsive">
                        <table class="table align-middle stack-table mb-0">
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
                                            {{ \Illuminate\Support\Facades\Lang::has('order.status.' . $order->status) ? __('order.status.' . $order->status) : $order->status }}
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
