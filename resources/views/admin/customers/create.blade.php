@extends('  layout.main')

@section('title', 'Tambah Pelanggan Baru')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Manajemen Pelanggan /</span> Tambah Pelanggan</h4>
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Formulir Pelanggan Baru</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.customers.store') }}" method="POST">
                @csrf
                @include('admin.customers._form')
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection