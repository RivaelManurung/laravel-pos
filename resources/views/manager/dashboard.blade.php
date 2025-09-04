@extends('layout.main')

@section('title', 'Dashboard Manager')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    {{-- Welcome Card --}}
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <h5 class="card-title text-primary">Selamat Datang, {{ Auth::user()->name }}! 👋</h5>
                            <p class="mb-4">
                                Berikut adalah ringkasan performa penjualan dan data penting lainnya untuk Anda review.
                            </p>
                        </div>
                    </div>
                    <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                            <img src="{{ asset('assets/img/illustrations/man-with-laptop-light.png') }}"
                                height="140" alt="Manager Dashboard" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Kartu Statistik Utama --}}
    <div class="row">
        {{-- Pendapatan Hari Ini --}}
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="d-block">Pendapatan Hari Ini</span>
                        <i class="bx bx-dollar-circle text-success fs-3"></i>
                    </div>
                    <h4 class="card-title text-success mb-1 mt-2">Rp {{ number_format($stats['today_sales'], 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>

        {{-- Pendapatan Bulan Ini --}}
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Pendapatan Bulan Ini</span>
                        <i class="bx bx-wallet text-primary fs-3"></i>
                    </div>
                    <h4 class="card-title text-primary mb-1 mt-2">Rp {{ number_format($stats['month_sales'], 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>

        {{-- Produk Stok Rendah --}}
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Produk Stok Rendah</span>
                        <i class="bx bx-box text-warning fs-3"></i>
                    </div>
                    <h4 class="card-title text-warning mb-1 mt-2">{{ $stats['low_stock_products'] }}</h4>
                </div>
            </div>
        </div>

        {{-- Total Pelanggan --}}
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Total Pelanggan</span>
                        <i class="bx bx-group text-info fs-3"></i>
                    </div>
                    <h4 class="card-title mb-1 mt-2">{{ $stats['total_customers'] }}</h4>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Transaksi Terbaru --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title m-0 me-2">Transaksi Terbaru</h5>
                    <a href="{{-- route('manager.orders.index') --}}" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No. Order</th>
                                <th>Pelanggan</th>
                                <th>Kasir</th>
                                <th>Total</th>
                                <th>Waktu Transaksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentOrders as $order)
                                <tr>
                                    <td>
                                        <a href="#"><strong>#{{ $order->order_number }}</strong></a>
                                    </td>
                                    <td>{{ $order->customer->name ?? 'N/A' }}</td>
                                    <td>{{ $order->user->name }}</td>
                                    <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                    <td>{{ $order->created_at->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada transaksi terbaru.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection