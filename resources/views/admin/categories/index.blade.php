@extends('layout.main')

@section('title', 'Manajemen Kategori')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Manajemen Kategori</h5>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                <i class="bx bx-plus me-1"></i> Tambah Kategori
            </button>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nama Kategori</th>
                        <th>Jumlah Produk</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($categories as $category)
                    <tr>
                        <td><strong>{{ $category->name }}</strong></td>
                        <td><span class="badge bg-label-info">{{ $category->products_count }}</span></td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item edit-category" data-bs-toggle="modal"
                                        data-bs-target="#editCategoryModal" data-id="{{ $category->id }}"
                                        data-name="{{ $category->name }}"
                                        data-update-url="{{ route('admin.categories.update', ['category' => 'PLACEHOLDER']) }}">
                                        <i class="bx bx-edit-alt me-1"></i> Edit
                                    </button>
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST"
                                        onsubmit="return confirm('Anda yakin ingin menghapus data ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bx bx-trash me-1"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center">Tidak ada data kategori.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3 px-3">
            {{ $categories->links() }}
        </div>
    </div>
</div>

{{-- Memanggil file modal --}}
@include('admin.categories.create')
@include('admin.categories.edit')
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.edit-category').forEach(button => {
            button.addEventListener('click', function () {
                const modal = document.querySelector('#editCategoryModal');
                const form = modal.querySelector('form');
                const categoryId = this.dataset.id;
                
                let updateUrl = this.dataset.updateUrl;
                
                form.action = updateUrl.replace('PLACEHOLDER', categoryId);
                
                modal.querySelector('#edit-name').value = this.dataset.name;
            });
        });
    });
</script>
@endpush