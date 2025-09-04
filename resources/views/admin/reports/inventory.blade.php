@extends('layout.main')

@section('title', 'Laporan Inventory')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Laporan Inventory</h5>
        </div>
        <div class="card-body">
            <h6>Produk</h6>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category->name ?? '-' }}</td>
                            <td>
                                @if($product->stock <= 5)
                                    <span class="badge bg-label-warning">{{ $product->stock }} (Rendah)</span>
                                @else
                                    {{ $product->stock }}
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <h6 class="mt-4">Produk dengan Stok Rendah</h6>
            @if($lowStockProducts->isEmpty())
                <p>Tidak ada produk dengan stok rendah.</p>
            @else
                <ul>
                    @foreach($lowStockProducts as $p)
                        <li>{{ $p->name }} — Stok: {{ $p->stock }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
@endsection
