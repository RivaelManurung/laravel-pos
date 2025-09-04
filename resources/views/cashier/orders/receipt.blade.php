@extends('layout.main')

@section('title', 'Struk Order')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card mx-auto" style="max-width:420px;">
        <div class="card-body">
            <div class="text-center mb-3">
                <h5 class="mb-0">{{ config('app.name', 'Laravel POS') }}</h5>
                <small class="text-muted">Struk Penjualan</small>
            </div>

            <p class="mb-1"><strong>Order #{{ $order->id }}</strong></p>
            <p class="mb-1">Waktu: {{ $order->created_at->format('Y-m-d H:i') }}</p>
            <p class="mb-1">Kasir: {{ $order->user->name ?? auth()->user()->name }}</p>
            <p class="mb-1">Pelanggan: {{ $order->customer->name ?? 'Walk-in' }}</p>

            <hr>

            <div class="table-responsive">
                <table class="table table-sm">
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td style="width:60%">{{ Str::limit($item->product->name ?? '-', 28) }}</td>
                            <td class="text-end">{{ $item->quantity }} x</td>
                            <td class="text-end">Rp {{ number_format($item->price * $item->quantity,0,',','.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <hr>

            <div class="d-flex justify-content-between mb-2">
                <div>Total</div>
                <div><strong>Rp {{ number_format($order->total,0,',','.') }}</strong></div>
            </div>

            <div class="d-flex justify-content-between mb-2">
                <div>Dibayar</div>
                <div>Rp {{ number_format($order->paid_amount ?? $order->total,0,',','.') }}</div>
            </div>

            <div class="d-flex justify-content-between mb-3">
                <div>Kembali</div>
                <div>Rp {{ number_format(($order->paid_amount ?? $order->total) - $order->total,0,',','.') }}</div>
            </div>

            <div class="text-center">
                <small class="text-muted">Terima kasih atas kunjungan Anda</small>
            </div>

            <div class="mt-3 d-flex justify-content-between">
                <a href="{{ route('cashier.orders.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
                <button class="btn btn-sm btn-primary" onclick="window.print()">Cetak</button>
            </div>
        </div>
    </div>
</div>
@endsection
