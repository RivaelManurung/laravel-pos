@extends('layout.main')

@section('title', 'Manajemen Produk')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Manajemen Produk</h5>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createProductModal">
                <i class="bx bx-plus me-1"></i> Tambah Produk
            </button>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($products as $product)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('assets/img/avatars/default-product.png') }}"
                                    alt="{{ $product->name }}" class="img-fluid rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                <div>
                                    <strong class="d-block">{{ $product->name }}</strong>
                                    <small class="text-muted">SKU: {{ $product->sku ?? '-' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>{{ $product->category->name ?? 'N/A' }}</td>
                        <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td>
                            @if($product->stock <= $product->min_stock)
                                <span class="badge bg-label-warning">{{ $product->stock }} (Rendah)</span>
                            @else
                                {{ $product->stock }}
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-label-{{ $product->is_active ? 'success' : 'secondary' }}">{{ $product->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item edit-product" 
                                        data-bs-toggle="modal"
                                        data-bs-target="#editProductModal" 
                                        data-id="{{ $product->id }}" 
                                        data-name="{{ $product->name }}"
                                        data-description="{{ $product->description }}"
                                        data-price="{{ $product->price }}"
                                        data-cost="{{ $product->cost }}"
                                        data-stock="{{ $product->stock }}"
                                        data-min_stock="{{ $product->min_stock }}"
                                        data-sku="{{ $product->sku }}"
                                        data-barcode="{{ $product->barcode }}"
                                        data-category_id="{{ $product->category_id }}"
                                        data-is_active="{{ $product->is_active }}"
                                        data-image_url="{{ $product->image ? asset('storage/' . $product->image) : '' }}"
                                        data-update_url="{{ route('admin.products.update', $product->id) }}">
                                        <i class="bx bx-edit-alt me-1"></i> Edit
                                    </button>
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus produk ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger"><i class="bx bx-trash me-1"></i> Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data produk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3 px-3">{{ $products->links() }}</div>
    </div>
</div>

{{-- Memanggil file modal --}}
@include('admin.products.create')
@include('admin.products.edit')

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editProductModal = document.getElementById('editProductModal');
        editProductModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const modal = this;
            const form = modal.querySelector('form');

            // Set form action URL
            form.action = button.dataset.update_url;

            // Populate form fields
            modal.querySelector('#edit-name').value = button.dataset.name;
            modal.querySelector('#edit-description').value = button.dataset.description;
            modal.querySelector('#edit-sku').value = button.dataset.sku;
            modal.querySelector('#edit-barcode').value = button.dataset.barcode;
            modal.querySelector('#edit-category_id').value = button.dataset.category_id;
            modal.querySelector('#edit-price').value = button.dataset.price;
            modal.querySelector('#edit-cost').value = button.dataset.cost;
            modal.querySelector('#edit-stock').value = button.dataset.stock;
            modal.querySelector('#edit-min_stock').value = button.dataset.min_stock;

            // Handle checkbox (is_active)
            const isActiveCheckbox = modal.querySelector('#edit-is_active');
            isActiveCheckbox.checked = button.dataset.is_active == 1;

            // Handle image preview
            const imagePreview = modal.querySelector('#edit-image-preview');
            const imageUrl = button.dataset.image_url;
            if (imageUrl) {
                imagePreview.src = imageUrl;
                imagePreview.style.display = 'block';
            } else {
                imagePreview.style.display = 'none';
            }
        });
    });
</script>
@endpush