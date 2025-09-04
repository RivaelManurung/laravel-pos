@extends('layout.main')

@section('title', 'Dashboard Admin POS')

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
                            <p class="mb-4">Anda login sebagai <strong>{{ ucfirst(Auth::user()->role) }}</strong>.
                                Berikut ringkasan data dari sistem Point of Sale.</p>
                        </div>
                    </div>
                    <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                            <img src="{{ asset('assets/img/illustrations/point-of-sale.png') }}" height="140"
                                alt="Point of Sale" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Kartu Statistik --}}
    <div class="row">
        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="d-block">Total Produk</span>
                        <i class="bx bx-package text-primary fs-3"></i>
                    </div>
                    {{-- Uses the corrected 'total_products' key --}}
                    <h4 class="card-title mb-1">{{ $stats['total_products'] }}</h4>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Pendapatan Hari Ini</span>
                        <i class="bx bx-dollar-circle text-success fs-3"></i>
                    </div>
                    {{-- Correctly displays the sum of today's sales --}}
                    <h4 class="card-title text-success mb-1">Rp {{ number_format($stats['today_sales'], 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Stok Rendah</span>
                        <i class="bx bx-error-alt text-warning fs-3"></i>
                    </div>
                    {{-- Displays the count of products with stock <= min_stock --}}
                    <h4 class="card-title text-warning mb-1">{{ $stats['low_stock_products'] }}</h4>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Total Pelanggan</span>
                        <i class="bx bx-user-circle text-info fs-3"></i>
                    </div>
                    <h4 class="card-title mb-1">{{ $stats['total_customers'] }}</h4>
                </div>
            </div>
        </div>
    </div>

    {{-- Baris untuk Grafik --}}
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Pendapatan (7 Hari Terakhir)</h5>
                </div>
                <div class="card-body">
                    <div id="revenueChart"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Produk Stok Rendah --}}
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title m-0 me-2">Produk Stok Rendah</h5>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Kategori</th>
                                <th>Stok Tersedia</th>
                                <th>Stok Minimum</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($lowStockProducts as $product)
                            <tr>
                                <td>{{ $product->name }}</td>
                                {{-- Works correctly due to eager loading in controller --}}
                                <td>{{ $product->category->name }}</td>
                                <td>{{ $product->stock }}</td>
                                <td>{{ $product->min_stock }}</td>
                                <td>
                                    <span class="badge bg-label-danger">Stok Rendah</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada produk dengan stok rendah.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Transaksi Terbaru --}}
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title m-0 me-2">5 Transaksi Terbaru</h5>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No. Order</th>
                                <th>Pelanggan</th>
                                <th>Kasir</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Now works because controller variable is $recentOrders --}}
                            @forelse ($recentOrders as $order)
                            <tr>
                                <td>{{ $order->order_number }}</td>
                                <td>{{ $order->customer->name ?? 'Tanpa Nama' }}</td>
                                <td>{{ $order->user->name }}</td>
                                <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                <td>
                                    @if($order->status == 'completed')
                                    <span class="badge bg-label-success">Selesai</span>
                                    @elseif($order->status == 'pending')
                                    <span class="badge bg-label-warning">Pending</span>
                                    @else
                                    <span class="badge bg-label-danger">Dibatalkan</span>
                                    @endif
                                </td>
                                <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada transaksi.</td>
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

@push('scripts')
{{-- This script section does not need any changes --}}
<script>
    $(function() {
        // Konfigurasi Grafik Pendapatan
        const revenueChartEl = document.querySelector('#revenueChart');
        if (revenueChartEl) {
            const revenueChartOptions = {
                chart: {
                    type: 'line',
                    height: 400,
                    toolbar: {
                        show: false
                    }
                },
                series: [{
                    name: 'Pendapatan',
                    data: {!! json_encode($salesData['data']) !!}
                }],
                xaxis: {
                    categories: {!! json_encode($salesData['labels']) !!},
                    labels: {
                        style: {
                            colors: '#697a8d'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        formatter: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        },
                        style: {
                            colors: '#697a8d'
                        }
                    }
                },
                colors: ['#696cff'],
                stroke: {
                    width: 3,
                    curve: 'smooth'
                },
                markers: {
                    size: 5
                },
                grid: {
                    borderColor: '#e7e7e7'
                }
            };

            const revenueChart = new ApexCharts(revenueChartEl, revenueChartOptions);
            revenueChart.render();
        }
    });
</script>
@endpush