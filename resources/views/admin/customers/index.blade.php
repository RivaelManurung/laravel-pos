@extends('layout.main')

@section('title', 'Manajemen Pelanggan')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Manajemen Pelanggan</h5>
            <a href="{{ route('admin.customers.create') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> Tambah Pelanggan
            </a>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Kontak</th>
                        <th>Total Transaksi</th>
                        <th>Tanggal Bergabung</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($customers as $customer)
                    <tr>
                        <td><strong>{{ $customer->name }}</strong></td>
                        <td>
                            <div class="d-flex flex-column">
                                <span>{{ $customer->email ?? '-' }}</span>
                                <small class="text-muted">{{ $customer->phone ?? '-' }}</small>
                            </div>
                        </td>
                        <td><span class="badge bg-label-info">{{ $customer->orders_count }} Transaksi</span></td>
                        <td>{{ $customer->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('admin.customers.show', $customer->id) }}"><i class="bx bx-show-alt me-1"></i> Lihat Detail</a>
                                    <a class="dropdown-item" href="{{ route('admin.customers.edit', $customer->id) }}"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                                    <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus pelanggan ini?');">
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
                        <td colspan="5" class="text-center">Tidak ada data pelanggan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3 px-3">
            {{ $customers->links() }}
        </div>
    </div>
</div>
@endsection