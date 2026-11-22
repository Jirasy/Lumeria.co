<?php


namespace App\Http\Controllers;


use App\Models\Product;
use Illuminate\View\View;


class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::active()->orderBy('id')->get()->groupBy('category');


        return view('pages.products', [
            'signature' => $products->get('bola_susu', collect()),
            'others'    => $products->get('lumer_lainnya', collect()),
        ]);
    }


    public function show(Product $product): View
    {
        abort_unless($product->is_active, 404);

        // Rekomendasi: kategori yang sama dulu, lalu sisanya.
        $related = Product::active()
            ->where('id', '!=', $product->id)
            ->orderByRaw('category = ? desc', [$product->category])
            ->orderBy('id')
            ->limit(3)
            ->get();

        return view('pages.product-show', [
            'product' => $product,
            'related' => $related,
        ]);
    }
}
