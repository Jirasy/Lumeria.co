<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\Cart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request, Cart $cart): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity'   => ['nullable', 'integer', 'min:1', 'max:99'],
        ]);

        $product = Product::active()->findOrFail($data['product_id']);
        $cart->add($product, $data['quantity'] ?? 1);

        return redirect()->route('order')
            ->with('status', __('order.added', ['name' => $product->name]));
    }

    public function update(Request $request, Product $product, Cart $cart): RedirectResponse
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:0', 'max:99'],
        ]);

        $cart->set($product, (int) $data['quantity']);

        return redirect()->route('order')->with('status', __('order.updated'));
    }

    public function remove(Product $product, Cart $cart): RedirectResponse
    {
        $cart->remove($product);

        return redirect()->route('order')->with('status', __('order.removed'));
    }
}