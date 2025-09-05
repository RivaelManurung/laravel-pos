<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

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

    public function downloadPdf(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke order ini.');
        }

        $order->load(['customer', 'items.product', 'user']);

        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = Pdf::loadView('cashier.orders.receipt_pdf', compact('order'));
            $filename = 'receipt-' . $order->id . '.pdf';
            return $pdf->download($filename);
        }

        // Fallback: log and redirect with instruction
        Log::warning('PDF generator not available. Install barryvdh/laravel-dompdf to enable PDF receipts.');

        return redirect()->route('cashier.orders.receipt', $order->id)
            ->with('warning', 'Fitur PDF belum tersedia. Untuk mengaktifkan, jalankan: composer require barryvdh/laravel-dompdf');
    }
}