@extends('layout.main')

@section('title', 'Detail Produk')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Detail Produk #{{ $product->id }}</h5>
            <a href="{{ route('manager.products.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('assets/img/avatars/default-product.png') }}" alt="{{ $product->name }}" class="img-fluid">
                </div>
                <div class="col-md-8">
                    <h4>{{ $product->name }}</h4>
                    <p>SKU: {{ $product->sku }}</p>
                    <p>Barcode: {{ $product->barcode }}</p>
                    <p>Harga: Rp {{ number_format($product->price,0,',','.') }}</p>
                    <p>Stok: {{ $product->stock }} (Min: {{ $product->min_stock }})</p>
                    <p>Kategori: {{ $product->category->name ?? '-' }}</p>
                    <p>Status: {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
