<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // It's good practice to import DB facade if using whereColumn

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function index()
    {
        $stats = [
            'total_products' => Product::count(), // Key name corrected from 'totalProduct'
            'total_customers' => Customer::count(),
            'today_sales' => Order::whereDate('created_at', Carbon::today())->sum('total'), // Changed count() to sum('total') for accurate revenue
            'total_orders' => Order::count(),
            'total_revenue' => Order::sum('total'),
            'total_users' => User::count(),
            'low_stock_products' => Product::whereColumn('stock', '<=', 'min_stock')->count(), // Made the logic dynamic instead of hardcoded
        ];

        // Variable name corrected from $recentsOrders to $recentOrders
        $recentOrders = Order::with(['customer', 'user'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Optimized query with eager loading for the category relationship
        $lowStockProducts = Product::with('category')
            ->whereColumn('stock', '<=', 'min_stock') // Logic updated to be dynamic
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();

        // Sales chart data (last 7 days)
        $salesData = [
            'labels' => [],
            'data' => [],
        ];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $salesData['labels'][] = $date->format('d M'); // Shortened format for better readability
            $salesData['data'][] = Order::whereDate('created_at', $date)->sum('total');
        }

        return view('admin.dashboard', compact('stats', 'recentOrders', 'salesData', 'lowStockProducts'));
    }
}