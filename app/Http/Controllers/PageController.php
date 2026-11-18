<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        return view('pages.home', [
            'signatureProducts' => Product::active()
                ->where('is_signature', true)
                ->orderBy('id')
                ->get(),
        ]);
    }

    public function islamic(): View
    {
        return view('pages.islamic');
    }

    public function economic(): View
    {
        return view('pages.economic');
    }

    public function team(): View
    {
    return view('pages.team', ['members' => config('lumeria.team')]);
    }
}