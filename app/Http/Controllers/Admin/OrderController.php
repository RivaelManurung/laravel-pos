<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function index()
    {
        $orders = Order::with(['customer', 'user', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['customer', 'user', 'items.product']);
        return view('admin.orders.show', compact('order'));
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->back()
            ->with('success', 'Order berhasil dihapus.');
    }
}