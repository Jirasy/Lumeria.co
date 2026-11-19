@php $rp = fn ($n) => 'Rp ' . number_format($n, 0, ',', '.'); @endphp
<div class="table-responsive">
    <table class="table table-sm align-middle mb-0 stack-table{{ $selectable ? ' stack-selectable' : '' }}">
        <thead>
            <tr>
                @if ($selectable)
                    <th scope="col"><span class="visually-hidden">{{ __('order.choose') }}</span></th>
                @endif
                <th scope="col">{{ __('order.tenor') }}</th>
                <th scope="col" class="text-end">{{ __('order.margin') }}</th>
                <th scope="col" class="text-end">{{ __('order.installment') }}</th>
                <th scope="col" class="text-end">{{ __('order.total_pay') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($plans as $plan)
                <tr>
                    @if ($selectable)
                        <td class="stack-radio">
                            <input class="form-check-input" type="radio" name="tenor_months"
                                   id="tenor-{{ $plan['tenor'] }}" value="{{ $plan['tenor'] }}"
                                   @checked((int) old('tenor_months', $plans[0]['tenor']) === $plan['tenor'])>
                        </td>
                    @endif
                    <td data-label="{{ __('order.tenor') }}" class="fw-semibold">
                        @if ($selectable)
                            <label for="tenor-{{ $plan['tenor'] }}">{{ $plan['tenor'] }} {{ __('order.months') }}</label>
                        @else
                            {{ $plan['tenor'] }} {{ __('order.months') }}
                        @endif
                    </td>
                    <td data-label="{{ __('order.margin') }}" class="text-sm-end">{{ $rp($plan['margin']) }}</td>
                    <td data-label="{{ __('order.installment') }}" class="text-sm-end fw-semibold">{{ $rp($plan['installment']) }}</td>
                    <td data-label="{{ __('order.total_pay') }}" class="text-sm-end">{{ $rp($plan['total']) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>