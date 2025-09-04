@extends('layout.main')

@section('title', 'Dashboard Kasir')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">POS</h5>
                    <p class="card-text">Buka antarmuka kasir untuk memproses penjualan cepat.</p>
                    <a href="{{ route('cashier.pos') }}" class="btn btn-primary">Buka POS</a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Order Saya</h5>
                    <p class="card-text">Lihat dan cetak order yang sudah dibuat oleh Anda.</p>
                    <a href="{{ route('cashier.orders.index') }}" class="btn btn-secondary">Lihat Order</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
