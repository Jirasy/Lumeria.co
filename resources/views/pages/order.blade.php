@extends('layouts.lumeria')


@section('title', __('order.title'))


@section('content')
    @php
        $rp = fn ($n) => 'Rp ' . number_format($n, 0, ',', '.');
        $rateText = rtrim(rtrim(number_format($marginRate, 2, '.', ''), '0'), '.');
        $method = old('payment_method', 'cash');
    @endphp


    <section class="py-4 py-md-5">
        <div class="container">
            <h1 class="fw-bold">{{ __('order.title') }}</h1>
            <p class="lead text-muted">{{ __('order.subtitle') }}</p>

            @guest
                {{-- Keranjang dan checkout hanya untuk pelanggan login; simulator Paylater tetap terbuka. --}}
                @php $waGuest = \App\Support\StoreContact::whatsapp(__('foodtech.footer.wa_message')); @endphp
                <div class="ft-note my-3">
                    <h2 class="h5">{{ __('foodtech.guest_order.title') }}</h2>
                    <p class="mb-3">{{ __('foodtech.guest_order.text') }}</p>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('login') }}" class="btn btn-lumeria">{{ __('nav.login') }}</a>
                        <a href="{{ route('register') }}" class="btn btn-outline-dark">{{ __('nav.register') }}</a>
                        @if ($waGuest)
                            <a href="{{ $waGuest }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-dark">{{ __('foodtech.footer.chat_wa') }}</a>
                        @endif
                    </div>
                </div>
            @endguest


            <div class="row g-4 mt-1">
                {{-- ===== Kolom kiri: keranjang + checkout ===== --}}
                <div class="col-lg-7">
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h2 class="h4 mb-3">{{ __('order.cart_title') }}</h2>


                            @if ($items->isEmpty())
                                <p class="text-muted">{{ __('order.cart_empty') }}</p>
                                <a href="{{ route('products') }}" class="btn btn-lumeria">{{ __('order.browse') }}</a>
                            @else
                                <div class="table-responsive">
                                    <table class="table align-middle stack-table mb-0">
                                        <thead>
                                            <tr>
                                                <th scope="col">{{ __('order.product') }}</th>
                                                <th scope="col" class="text-end">{{ __('order.price') }}</th>
                                                <th scope="col">{{ __('order.qty') }}</th>
                                                <th scope="col" class="text-end">{{ __('order.line_total') }}</th>
                                                <th scope="col"><span class="visually-hidden">{{ __('order.remove') }}</span></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($items as $row)
                                                @php $p = $row['product']; @endphp
                                                <tr>
                                                    <td data-label="{{ __('order.product') }}" class="fw-semibold">{{ $p->name }}</td>
                                                    <td data-label="{{ __('order.price') }}" class="text-sm-end">{{ $rp($p->price) }}</td>
                                                    <td data-label="{{ __('order.qty') }}" style="min-width: 9rem">
                                                        <form method="POST" action="{{ route('cart.update', $p) }}" class="d-flex gap-1">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="number" name="quantity" value="{{ $row['quantity'] }}"
                                                                   min="0" max="99" inputmode="numeric"
                                                                   class="form-control form-control-sm"
                                                                   aria-label="{{ __('order.qty') }}">
                                                            <button type="submit" class="btn btn-sm btn-outline-dark">{{ __('order.update') }}</button>
                                                        </form>
                                                    </td>
                                                    <td data-label="{{ __('order.line_total') }}" class="text-sm-end fw-semibold">{{ $rp($row['line_total']) }}</td>
                                                    <td data-label="" class="text-sm-end">
                                                        <form method="POST" action="{{ route('cart.remove', $p) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                                    aria-label="{{ __('order.remove') }}: {{ $p->name }}">{{ __('order.remove') }}</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3" class="text-sm-end">{{ __('order.subtotal') }}</th>
                                                <th class="text-sm-end">{{ $rp($subtotal) }}</th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>


                    @unless ($items->isEmpty())
                        <form method="POST" action="{{ route('order.store') }}" class="card shadow-sm">
                            @csrf
                            <div class="card-body">
                                <h2 class="h4 mb-3">{{ __('order.checkout_title') }}</h2>


                                <div class="mb-3">
                                    <label for="customer_name" class="form-label">{{ __('order.name') }}</label>
                                    <input id="customer_name" type="text" name="customer_name" required maxlength="100"
                                           autocomplete="name"
                                           value="{{ old('customer_name', auth()->user()?->name) }}"
                                           class="form-control @error('customer_name') is-invalid @enderror">
                                    @error('customer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>


                                <div class="mb-3">
                                    <label for="customer_phone" class="form-label">{{ __('order.phone') }}</label>
                                    <input id="customer_phone" type="tel" name="customer_phone" required maxlength="20"
                                           inputmode="tel" autocomplete="tel"
                                           value="{{ old('customer_phone') }}"
                                           class="form-control @error('customer_phone') is-invalid @enderror">
                                    @error('customer_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>


                                <div class="mb-3">
                                    <label for="customer_address" class="form-label">{{ __('order.address') }}</label>
                                    <textarea id="customer_address" name="customer_address" rows="2" maxlength="500"
                                              autocomplete="street-address"
                                              class="form-control @error('customer_address') is-invalid @enderror">{{ old('customer_address') }}</textarea>
                                    @error('customer_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>


                                <fieldset class="mb-3">
                                    <legend class="form-label fs-6">{{ __('order.payment_method') }}</legend>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_method"
                                               id="pay-cash" value="cash" @checked($method === 'cash')>
                                        <label class="form-check-label" for="pay-cash">{{ __('order.methods.cash') }}</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_method"
                                               id="pay-paylater" value="paylater" @checked($method === 'paylater')>
                                        <label class="form-check-label" for="pay-paylater">{{ __('order.methods.paylater') }}</label>
                                    </div>
                                </fieldset>


                                <div id="paylater-box" class="border rounded p-3 mb-3 bg-light">
                                    <h3 class="h6">{{ __('order.paylater_title') }}</h3>
                                    @include('partials.plans-table', ['plans' => $plans, 'selectable' => true])
                                    <p class="small text-muted mt-2 mb-0">{{ __('order.rounding_note') }}</p>
                                </div>


                                <button type="submit" class="btn btn-lumeria btn-lg w-100">{{ __('order.place_order') }}</button>
                            </div>
                        </form>
                    @endunless
                </div>


                {{-- ===== Kolom kanan: simulator + rumus ===== --}}
                <div class="col-lg-5">
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h2 class="h4 mb-3">{{ __('order.sim_title') }}</h2>


                            <form method="GET" action="{{ route('order') }}" class="row g-2 mb-3">
                                <div class="col">
                                    <label for="amount" class="visually-hidden">{{ __('order.sim_amount') }}</label>
                                    <input id="amount" type="number" name="amount" min="1000" max="100000000"
                                           inputmode="numeric" placeholder="{{ __('order.sim_amount') }}"
                                           value="{{ request('amount') }}" class="form-control">
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-lumeria">{{ __('order.sim_button') }}</button>
                                </div>
                            </form>


                            @if ($simulation)
                                <h3 class="h6">{{ __('order.sim_result', ['amount' => $rp($simulation['amount'])]) }}</h3>
                                @include('partials.plans-table', ['plans' => $simulation['plans'], 'selectable' => false])
                                <p class="small text-muted mt-2 mb-0">{{ __('order.rounding_note') }}</p>
                            @endif
                        </div>
                    </div>


                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h2 class="h5">{{ __('order.formula_title') }}</h2>
                            <ol class="ps-3 mb-2">
                                <li>{{ __('order.formula_margin', ['rate' => $rateText]) }}</li>
                                <li>{{ __('order.formula_installment') }}</li>
                                <li>{{ __('order.formula_total') }}</li>
                            </ol>
                            <p class="small text-muted mb-0">{{ __('order.formula_fixed') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


@push('scripts')
    <script>
        (function () {
            var box = document.getElementById('paylater-box');
            if (!box) { return; }


            function sync() {
                var chosen = document.querySelector('input[name="payment_method"]:checked');
                box.classList.toggle('d-none', !(chosen && chosen.value === 'paylater'));
            }


            document.querySelectorAll('input[name="payment_method"]').forEach(function (r) {
                r.addEventListener('change', sync);
            });
            sync();
        })();
    </script>
@endpush
