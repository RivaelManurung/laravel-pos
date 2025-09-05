<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function index()
    {
        // Mengambil semua data yang dibutuhkan oleh halaman index (tabel & modal)
        $products = Product::with('category')->latest()->paginate(10);
        $categories = Category::orderBy('name')->get(); // Diperlukan untuk form tambah/edit
        return view('admin.products.index', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        // (Logika tidak berubah)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'cost' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'nullable|integer|min:0',
            'sku' => 'nullable|string|unique:products,sku',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        // Server-side barcode generation: ensure PRD-<10digits> and uniqueness
        if (empty($validated['barcode'])) {
            $validated['barcode'] = $this->generateUniqueBarcode();
        } else {
            // Ignore client-provided barcode and still ensure uniqueness
            // to prevent tampering; generate new one if collision
            if (Product::where('barcode', $validated['barcode'])->exists()) {
                $validated['barcode'] = $this->generateUniqueBarcode();
            }
        }

        $validated['is_active'] = $request->has('is_active');
        Product::create($validated);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, Product $product)
    {
        // (Logika tidak berubah)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'cost' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'nullable|integer|min:0',
            'sku' => 'nullable|string|unique:products,sku,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean'
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

    // Do not allow updating barcode from client-side. Keep existing barcode.
    unset($validated['barcode']);

    $validated['is_active'] = $request->has('is_active');
    $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        // (Logika tidak berubah)
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return redirect()->back()->with('success', 'Produk berhasil dihapus.');
    }

    /**
     * Generate a unique PRD-<10digit> barcode.
     */
    protected function generateUniqueBarcode()
    {
        do {
            $num = mt_rand(1000000000, 9999999999); // 10 digits
            $code = 'PRD-' . $num;
        } while (Product::where('barcode', $code)->exists());

        return $code;
    }
}