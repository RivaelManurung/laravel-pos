@extends('layout.main')

@section('title', 'Produk - Manager')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Produk</h5>
            <div>
                <form class="d-flex" method="GET">
                    <input name="q" value="{{ $q ?? '' }}" class="form-control form-control-sm me-2" placeholder="Cari nama, SKU atau barcode">
                    <button class="btn btn-sm btn-outline-primary me-2">Cari</button>
                    <a href="{{ route('manager.products.export') }}" class="btn btn-sm btn-secondary">Export CSV</a>
                </form>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>SKU</th>
                        <th>Barcode</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->sku }}</td>
                        <td>{{ $product->barcode }}</td>
                        <td>Rp {{ number_format($product->price,0,',','.') }}</td>
                        <td>{{ $product->stock }}</td>
                        <td>{{ $product->category->name ?? '-' }}</td>
                        <td>{{ $product->is_active ? 'Aktif' : 'Nonaktif' }}</td>
                        <td>
                            <a href="{{ route('manager.products.show', $product->id) }}" class="btn btn-sm btn-primary">Lihat</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center">Tidak ada produk.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">{{ $products->links() }}</div>
    </div>
</div>
@endsection
