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
}