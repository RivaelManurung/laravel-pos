<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt #{{ $order->id }}</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size:12px; }
        .container { width: 100%; max-width: 380px; margin: 0 auto; }
        .center { text-align:center; }
        table { width:100%; border-collapse:collapse; }
        td { padding:4px 0; }
        .right { text-align:right; }
        hr { border: none; border-top: 1px solid #ccc; margin:8px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="center">
            <h4 style="margin:0">{{ config('app.name', 'Laravel POS') }}</h4>
            <div style="font-size:11px;color:#666">Struk Penjualan</div>
        </div>

        <p style="margin:8px 0 4px 0">Order: <strong>#{{ $order->id }}</strong></p>
        <p style="margin:0 0 4px 0">Waktu: {{ $order->created_at->format('Y-m-d H:i') }}</p>
        <p style="margin:0 0 8px 0">Kasir: {{ $order->user->name ?? auth()->user()->name }}</p>

        <hr>

        <table>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td style="width:60%">{{ 
                        Illuminate\Support\Str::limit($item->product->name ?? '-', 28) }}</td>
                    <td class="right">{{ $item->quantity }} x</td>
                    <td class="right">Rp {{ number_format($item->price * $item->quantity,0,',','.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <hr>

        <table>
            <tr>
                <td>Total</td>
                <td class="right">Rp {{ number_format($order->total,0,',','.') }}</td>
            </tr>
            <tr>
                <td>Dibayar</td>
                <td class="right">Rp {{ number_format($order->paid_amount ?? $order->total,0,',','.') }}</td>
            </tr>
            <tr>
                <td>Kembali</td>
                <td class="right">Rp {{ number_format(($order->paid_amount ?? $order->total) - $order->total,0,',','.') }}</td>
            </tr>
        </table>

        <hr>
        <div class="center" style="font-size:11px;color:#666">Terima kasih atas kunjungan Anda</div>
    </div>
</body>
</html>
