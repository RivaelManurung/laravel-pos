<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:cashier');
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

            $order = Order::create([
                'order_number' => 'ORD' . date('YmdHis'),
                'customer_id' => $validated['customer_id'] ?? null,
                'user_id' => auth()->id(),
                'subtotal' => $subtotal,
                'tax' => $tax,
                'discount' => $discount,
                'total' => $total,
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'paid',
                'status' => 'completed',
                'notes' => $validated['notes'] ?? null
            ]);

            foreach ($orderItems as &$orderItem) {
                $orderItem['order_id'] = $order->id;
                $orderItem['created_at'] = now();
                $orderItem['updated_at'] = now();
            }
            
            OrderItem::insert($orderItems);

            DB::commit();

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'total' => $total
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}