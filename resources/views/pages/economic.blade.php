@extends('layouts.lumeria')

@section('title', __('nav.economic'))

@section('content')
    @php
        $rate   = rtrim(rtrim(number_format((float) config('lumeria.paylater.margin_rate'), 2, '.', ''), '0'), '.');
        $tenors = implode(', ', config('lumeria.paylater.tenors'));
    @endphp

    <section class="py-5">
        <article class="container" style="max-width: 52rem" lang="en">
            <p class="text-lumeria fw-semibold mb-1">Economic Discussion &middot; Discussion Text</p>
            <h1 class="fw-bold">Should Businesses Offer Paylater? Interest Rates and Installments in Modern Business</h1>

            <h2 class="h3 fw-bold mt-5">Issue</h2>
            <p>Paylater lets customers take their goods today and pay in installments later. Large online stores and small snack sellers now offer it. However, installments usually come with an interest rate or a service fee. This raises a question: is Paylater a helpful financial service, or a risky way of encouraging people to spend money they do not yet have?</p>

            <h2 class="h3 fw-bold mt-5">Key Terms</h2>
            <dl>
                <dt>Interest rate</dt>
                <dd>A percentage charged for using money over a period of time.</dd>
                <dt>Flat rate</dt>
                <dd>A percentage calculated on the original amount for the whole term.</dd>
                <dt>Effective rate</dt>
                <dd>A percentage calculated on the balance the buyer still owes, so it shows the real cost of borrowing.</dd>
                <dt>Margin</dt>
                <dd>Lumeria.co&rsquo;s word for the fixed extra amount added to the price when a customer pays in installments.</dd>
            </dl>

            <h2 class="h3 fw-bold mt-5">Arguments For</h2>
            <ol>
                <li><strong>It widens access.</strong> Splitting a payment into small parts lets students and low-income customers buy things they could not pay for at once.</li>
                <li><strong>It supports small businesses.</strong> Sellers who offer installments can keep customers who would otherwise walk away because of the price.</li>
                <li><strong>It can be transparent.</strong> When the term is short and the extra cost is shown before checkout, buyers know exactly what they will pay. Lumeria.co shows the margin, the monthly installment, and the total before an order is placed.</li>
                <li><strong>Short plans are manageable.</strong> A plan of {{ $tenors }} months is easier to plan around than a long loan, so the buyer&rsquo;s commitment stays small.</li>
            </ol>

            <h2 class="h3 fw-bold mt-5">Arguments Against</h2>
            <ol>
                <li><strong>It encourages overspending.</strong> Paying later makes spending feel painless, so people may buy more than they can afford, especially young buyers who are still learning to manage money.</li>
                <li><strong>A flat rate can hide the real cost.</strong> A flat 2% per month looks small, but the buyer repays part of the price every month while the margin is still calculated on the full amount. The effective cost is therefore higher than the number on the label (see the example below).</li>
                <li><strong>The seller carries risk.</strong> Late or unpaid installments hurt a small business that has little cash to spare, and collecting payments takes time.</li>
                <li><strong>There are ethical and religious concerns.</strong> Many Muslim scholars consider interest on loans to be <em>riba</em>, which is prohibited, and penalties that grow over time can trap buyers in debt. Even from other points of view, unclear fees are a question of fairness.</li>
            </ol>

            <h2 class="h3 fw-bold mt-5">Worked Example</h2>
            <div class="card shadow-sm">
                <div class="card-body">
                    <p>A customer buys Rp 36,000 of snacks and pays over 3 months with a flat margin of 2% per month.</p>
                    <ul>
                        <li>Margin = 36,000 &times; 2% &times; 3 = Rp 2,160</li>
                        <li>Total payment = Rp 38,160, or Rp 12,720 per month</li>
                    </ul>
                    <p class="mb-0">The customer pays 6% more than the cash price, but owes the full amount only during the first month. Measured on the balance the customer actually owes, the cost works out to roughly 3% per month.</p>
                </div>
            </div>

            <h2 class="h3 fw-bold mt-5">Conclusion and Recommendation</h2>
            <p>Paylater is neither purely good nor purely bad; it depends on how it is designed. Businesses should offer it as a transparent option, not as a default push. We recommend that sellers:</p>
            <ol>
                <li>keep the terms short;</li>
                <li>show the margin, the installment, and the total price before the customer commits;</li>
                <li>fix the margin at the start and never let it grow with delays;</li>
                <li>explain the difference between flat and effective rates in simple words; and</li>
                <li>encourage customers to compare the installment total with the cash price.</li>
            </ol>
            <p>Lumeria.co follows these rules in its simulator, with a flat margin of {{ $rate }}% per month and terms of {{ $tenors }} months. Customers who can pay in cash should do so, because even the cheapest installment plan costs more than the cash price.</p>
        </article>
    </section>
@endsection