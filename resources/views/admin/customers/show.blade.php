@extends('layout.main')

@section('title', 'Detail Pelanggan')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Manajemen Pelanggan /</span> Detail</h4>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                        <div class="button-wrapper">
                            <h4 class="mb-2">{{ $customer->name }}</h4>
                            <p class="text-muted mb-1"><i class="bx bx-envelope me-2"></i>{{ $customer->email ?? '-' }}</p>
                            <p class="text-muted mb-1"><i class="bx bx-phone me-2"></i>{{ $customer->phone ?? '-' }}</p>
                            <p class="text-muted mb-0"><i class="bx bx-map me-2"></i>{{ $customer->address ?? 'Alamat tidak diisi.' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Riwayat Transaksi</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No. Order</th>
                        <th>Tanggal</th>
                        <th>Kasir</th>
                        <th>Total Belanja</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customer->orders as $order)
                        <tr>
                            <td><strong>#{{ $order->order_number }}</strong></td>
                            <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                            <td>{{ $order->user->name }}</td>
                            <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge bg-label-{{ $order->status == 'completed' ? 'success' : 'warning' }}">{{ ucfirst($order->status) }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Pelanggan ini belum memiliki riwayat transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection