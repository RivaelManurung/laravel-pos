<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:manager');
    }

    public function index()
    {
        $stats = [
            'today_sales' => Order::whereDate('created_at', Carbon::today())->sum('total'),
            'month_sales' => Order::whereMonth('created_at', Carbon::now()->month)->sum('total'),
            'total_customers' => Customer::count(),
            'low_stock_products' => Product::where('stock', '<=', 5)->count(),
        ];

        $recentOrders = Order::with(['customer', 'user'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('manager.dashboard', compact('stats', 'recentOrders'));
    }
}