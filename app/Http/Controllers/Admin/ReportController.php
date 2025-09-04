<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function salesReport(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        $orders = Order::with(['customer', 'items.product'])
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalSales = $orders->sum('total');
        $totalOrders = $orders->count();

        return view('admin.reports.sales', compact('orders', 'totalSales', 'totalOrders', 'startDate', 'endDate'));
    }

    public function inventoryReport()
    {
        $products = Product::with('category')
            ->orderBy('stock', 'asc')
            ->get();

        $lowStockProducts = $products->where('stock', '<=', 5);

        return view('admin.reports.inventory', compact('products', 'lowStockProducts'));
    }
}