<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:cashier');
    }

    public function index()
    {
        $orders = Order::with(['customer', 'items.product'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('cashier.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke order ini.');
        }

        $order->load(['customer', 'user', 'items.product']);

        return view('cashier.orders.show', compact('order'));
    }

    public function printReceipt(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke order ini.');
        }

        $order->load(['customer', 'items.product']);

        return view('cashier.orders.receipt', compact('order'));
    }
}