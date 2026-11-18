<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\Cart;
use App\Services\PaylaterCalculator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request, Cart $cart, PaylaterCalculator $calc): View
    {
        $items    = $cart->items();
        $subtotal = (int) $items->sum('line_total');

        // Simulator bebas: /order?amount=50000 (tidak menyentuh keranjang)
        $amount     = $request->integer('amount');
        $simulation = ($amount >= 1000 && $amount <= 100_000_000)
            ? ['amount' => $amount, 'plans' => $calc->plans($amount)]
            : null;

        return view('pages.order', [
            'items'      => $items,
            'subtotal'   => $subtotal,
            'plans'      => $subtotal > 0 ? $calc->plans($subtotal) : [],
            'simulation' => $simulation,
            'marginRate' => $calc->rate(),
            'tenors'     => $calc->tenors(),
        ]);
    }

    public function store(Request $request, Cart $cart, PaylaterCalculator $calc): RedirectResponse
    {
        $items = $cart->items();

        if ($items->isEmpty()) {
            return redirect()->route('order')->with('error', __('order.empty_cart'));
        }

        $data = $request->validate([
            'customer_name'    => ['required', 'string', 'max:100'],
            'customer_phone'   => ['required', 'string', 'regex:/^[0-9+\-\s]{8,20}$/'],
            'customer_address' => ['nullable', 'string', 'max:500'],
            'payment_method'   => ['required', Rule::in(['cash', 'paylater'])],
            'tenor_months'     => ['required_if:payment_method,paylater', 'nullable', 'integer', Rule::in($calc->tenors())],
        ]);

        // Semua angka dihitung ulang di server; input dari form hanya memilih tenor.
        $subtotal = (int) $items->sum('line_total');
        $plan     = $data['payment_method'] === 'paylater'
            ? $calc->calculate($subtotal, (int) $data['tenor_months'])
            : null;

        $order = DB::transaction(function () use ($data, $items, $subtotal, $plan) {
            $order = Order::create([
                'user_id'            => auth()->id(),
                'customer_name'      => $data['customer_name'],
                'customer_phone'     => $data['customer_phone'],
                'customer_address'   => $data['customer_address'] ?? null,
                'subtotal'           => $subtotal,
                'payment_method'     => $data['payment_method'],
                'tenor_months'       => $plan['tenor'] ?? null,
                'margin_rate'        => $plan['rate'] ?? null,
                'margin_total'       => $plan['margin'] ?? 0,
                'installment_amount' => $plan['installment'] ?? null,
                'grand_total'        => $plan['total'] ?? $subtotal,
                'status'             => 'pending',
            ]);

            foreach ($items as $row) {
                $order->items()->create([
                    'product_id'   => $row['product']->id,
                    'product_name' => $row['product']->name,
                    'price'        => $row['product']->price,
                    'quantity'     => $row['quantity'],
                    'line_total'   => $row['line_total'],
                ]);
            }

            return $order;
        });

        $cart->clear();
        session(['last_order_id' => $order->id]);

        return redirect()->route('order.show', $order)->with('status', __('order.placed'));
    }

    public function show(Order $order): View
    {
        $owns = auth()->check()
            && $order->user_id !== null
            && (int) $order->user_id === (int) auth()->id();

        $justPlaced = (int) session('last_order_id') === (int) $order->id;

        // Tanpa ini, siapa pun bisa menebak /order/1, /order/2 dan membaca nama, telepon, alamat orang lain.
        abort_unless($owns || $justPlaced, 403);

        return view('pages.order-show', ['order' => $order->load('items')]);
    }
}