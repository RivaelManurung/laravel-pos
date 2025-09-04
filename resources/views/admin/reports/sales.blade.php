@extends('layout.main')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Laporan Penjualan</h5>
        </div>
        <div class="card-body">
            <p><strong>Periode:</strong> {{ $startDate }} - {{ $endDate }}</p>
            <p><strong>Total Orders:</strong> {{ $totalOrders }}</p>
            <p><strong>Total Penjualan:</strong> Rp {{ number_format($totalSales,0,',','.') }}</p>

            <h6 class="mt-3">Daftar Order</h6>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Waktu</th>
                            <th>Pelanggan</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                            <td>{{ $order->customer->name ?? 'Walk-in' }}</td>
                            <td>Rp {{ number_format($order->total,0,',','.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada order pada periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
