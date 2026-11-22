<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'stats' => [
                'products'  => Product::active()->count(),
                'orders'    => Order::count(),
                'pending'   => Order::where('status', 'pending')->count(),
                'customers' => User::where('role', User::ROLE_CUSTOMER)->count(),
            ],
            'latestOrders' => Order::withCount('items')->latest()->limit(10)->get(),
            'products'     => Product::orderBy('category')->orderBy('id')->get(),
        ]);
    }
}
