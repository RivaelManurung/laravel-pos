@extends('layout.main')

@section('title', 'Order Saya')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if(session('warning'))<div class="alert alert-warning">{{ session('warning') }}</div>@endif
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Order Saya</h5>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Waktu</th>
                        <th>Pelanggan</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                        <td>{{ $order->customer->name ?? 'Walk-in' }}</td>
                        <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                        <td><span class="badge bg-label-{{ $order->status == 'paid' ? 'success' : 'secondary' }}">{{ ucfirst($order->status) }}</span></td>
                        <td>
                            <a href="{{ route('cashier.orders.show', $order->id) }}" class="btn btn-sm btn-primary">Lihat</a>
                            <a href="{{ route('cashier.orders.receipt', $order->id) }}" class="btn btn-sm btn-secondary">Struk</a>
                            <a href="{{ route('cashier.orders.pdf', $order->id) }}" class="btn btn-sm btn-outline-primary">PDF</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada order.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3 px-3">{{ $orders->links() }}</div>
    </div>
</div>
@endsection
