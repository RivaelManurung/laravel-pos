<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PosController extends Controller
{
    public function __construct()
    {
    // Exclude webhook notify from role middleware so external Midtrans can post to it
    $this->middleware('role:cashier')->except('midtransNotify');
    }

    public function index()
    {
        $products = Product::where('is_active', true)
            ->where('stock', '>', 0)
            ->orderBy('name')
            ->get();

        $customers = Customer::orderBy('name')->get();

        return view('cashier.pos.index', compact('products', 'customers'));
    }

    public function getProduct($id)
    {
        $product = Product::findOrFail($id);
        return response()->json($product);
    }

    public function processOrder(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,card,transfer',
            'notes' => 'nullable|string'
        ]);

        DB::beginTransaction();

    try {
            $items = $validated['items'];
            $subtotal = 0;
            $orderItems = [];

            foreach ($items as $item) {
                $product = Product::findOrFail($item['product_id']);
                
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stok {$product->name} tidak mencukupi.");
                }

                $price = $product->price;
                $total = $price * $item['quantity'];
                $subtotal += $total;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $price,
                    'total' => $total
                ];

                $product->decrement('stock', $item['quantity']);
            }

            $tax = 0;
            $discount = 0;
            $total = $subtotal + $tax - $discount;

            // For non-cash payments we'll mark the order as pending and create a Midtrans Snap token
            $orderData = [
                'order_number' => 'ORD' . date('YmdHis'),
                'customer_id' => $validated['customer_id'] ?? null,
                'user_id' => auth()->id(),
                'subtotal' => $subtotal,
                'tax' => $tax,
                'discount' => $discount,
                'total' => $total,
                'payment_method' => $validated['payment_method'],
                'payment_status' => $validated['payment_method'] === 'cash' ? 'paid' : 'pending',
                'status' => $validated['payment_method'] === 'cash' ? 'completed' : 'pending',
                'notes' => $validated['notes'] ?? null
            ];

            $order = Order::create($orderData);

            foreach ($orderItems as &$orderItem) {
                $orderItem['order_id'] = $order->id;
                $orderItem['created_at'] = now();
                $orderItem['updated_at'] = now();
            }
            
            OrderItem::insert($orderItems);

            // If payment method is card or transfer, create Midtrans Snap transaction and return snap token
            if (in_array($validated['payment_method'], ['card', 'transfer'])) {
                // Prepare payload for Midtrans Snap
                try {
                    $midtransResponse = $this->createMidtransTransaction($order);
                    DB::commit();

                    return response()->json([
                        'success' => true,
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                        'total' => $total,
                        'snap_token' => $midtransResponse['token'] ?? null,
                        'client_key' => $midtransResponse['client_key'] ?? env('MIDTRANS_CLIENT_KEY')
                    ]);
                } catch (\Exception $e) {
                    DB::rollBack();
                    // Log full exception for server-side debugging, but return a safe message to client
                    Log::error('Midtrans create transaction failed', ['exception' => $e]);
                    return $this->jsonError('Gagal membuat transaksi pembayaran. Silakan coba lagi atau hubungi admin.', 500);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'total' => $total
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('processOrder failed', ['exception' => $e]);
            return $this->jsonError($e->getMessage(), 500);
        }
    }

    /**
     * Create Midtrans Snap transaction and return token
     */
    protected function createMidtransTransaction(Order $order)
    {
        $serverKey = config('midtrans.server_key');
        $clientKey = config('midtrans.client_key');
        $isProduction = config('midtrans.production', false);

        if (empty($serverKey)) {
            throw new \Exception('Midtrans server key (MIDTRANS_SERVER_KEY) not configured in .env or config/midtrans.php');
        }

        $payload = [
            'transaction_details' => [
                'order_id' => $order->order_number,
                'gross_amount' => (int) $order->total
            ],
            'customer_details' => [
                'first_name' => $order->customer?->name ?? 'Customer',
                'email' => $order->customer?->email ?? null,
                'phone' => $order->customer?->phone ?? null,
            ],
            'item_details' => $order->items()->with('product')->get()->map(function($it){
                return [
                    'id' => $it->product_id,
                    'price' => (int) $it->price,
                    'quantity' => (int) $it->quantity,
                    'name' => $it->product->name ?? 'Item'
                ];
            })->toArray()
        ];

    $base = $isProduction ? 'https://app.midtrans.com' : 'https://app.sandbox.midtrans.com';

        $response = Http::withBasicAuth($serverKey, '')
            ->post($base . '/snap/v1/transactions', $payload);

        // Attempt to parse JSON body for better decision making
        $data = null;
        try {
            $data = $response->json();
        } catch (\Throwable $t) {
            // ignore JSON parse errors, we'll handle below
        }

        // Consider the request successful if the HTTP client considers it successful
        // or if the response body contains a Midtrans snap token (defensive for odd statuses)
        $hasToken = is_array($data) && array_key_exists('token', $data);
        if (!$response->successful() && !$hasToken) {
            // Log response details for debugging (do not log secret keys)
            Log::error('Midtrans API responded with non-OK status', [
                'status' => $response->status(),
                'body_snippet' => mb_substr($response->body(), 0, 1000),
            ]);

            $bodyForMessage = $response->body() ?: json_encode($data ?: []);
            throw new \Exception('Midtrans API error: ' . $bodyForMessage);
        }
    // return token and client key for frontend
        return [
            'token' => $data['token'] ?? null,
            'redirect_url' => $data['redirect_url'] ?? null,
            'client_key' => $clientKey,
            'merchant_id' => config('midtrans.merchant_id')
        ];
    }

    /**
     * Midtrans notification webhook endpoint
     */
    public function midtransNotify(Request $request)
    {
        $payload = $request->all();

        // Try to find order by order_number inside transaction_details or order_id
        $orderNumber = data_get($payload, 'order_id') ?? data_get($payload, 'transaction_details.order_id');
        if (!$orderNumber) {
            return response()->json(['status' => 'ignored']);
        }

        $order = Order::where('order_number', $orderNumber)->first();
        if (!$order) {
            Log::warning('midtransNotify: order not found for order_number', ['order_number' => $orderNumber, 'payload' => array_slice($payload, 0, 10)]);
            return response()->json(['status' => 'not_found'], 404);
        }

        $status = $payload['transaction_status'] ?? $payload['status_code'] ?? null;

        // Simplified mapping
        if (in_array($status, ['capture', 'settlement', '200'])) {
            $order->payment_status = 'paid';
            $order->status = 'completed';
            $order->save();
        } elseif (in_array($status, ['deny', 'cancel', 'expired', '201', '202'])) {
            $order->payment_status = 'failed';
            $order->status = 'cancelled';
            $order->save();
        } else {
            // keep pending
        }

        Log::info('midtransNotify processed', ['order_id' => $order->id, 'status' => $status]);
        return response()->json(['status' => 'ok']);
    }

    /**
     * Standard JSON error response helper
     */
    private function jsonError(string $message = 'Terjadi kesalahan', int $status = 500)
    {
        return response()->json([
            'success' => false,
            'message' => $message
        ], $status);
    }
}