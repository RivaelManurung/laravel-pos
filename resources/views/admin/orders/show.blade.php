@extends('layout.main')

@section('title', 'Detail Order')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Detail Order #{{ $order->id }}</h5>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
        </div>
        <div class="card-body">
            <p><strong>Waktu:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>
            <p><strong>Pelanggan:</strong> {{ $order->customer->name ?? 'Walk-in' }}</p>
            <p><strong>User:</strong> {{ $order->user->name ?? '-' }}</p>
            <p><strong>Total:</strong> Rp {{ number_format($order->total,0,',','.') }}</p>

            <h6>Items</h6>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->product->name ?? '-' }}</td>
                            <td>Rp {{ number_format($item->price,0,',','.') }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>Rp {{ number_format($item->price * $item->quantity,0,',','.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
