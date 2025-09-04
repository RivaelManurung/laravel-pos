@extends('layout.main')

@section('title', 'Edit Pelanggan')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Manajemen Pelanggan /</span> Edit Pelanggan</h4>
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Formulir Edit Pelanggan</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.customers.update', $customer->id) }}" method="POST">
                @csrf
                @method('PUT')
                @include('admin.customers._form')
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection