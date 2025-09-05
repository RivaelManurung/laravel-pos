<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:manager');
    }

    public function index(Request $request)
    {
        $q = $request->query('q');

        $products = Product::with('category')
            ->when($q, function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('sku', 'like', "%{$q}%")
                      ->orWhere('barcode', 'like', "%{$q}%");
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('manager.products.index', compact('products', 'q'));
    }

    public function show(Product $product)
    {
        $product->load('category');
        return view('manager.products.show', compact('product'));
    }

    public function export(Request $request)
    {
        $filename = 'products_export_' . date('Ymd_His') . '.csv';

        $response = new StreamedResponse(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['id', 'name', 'sku', 'barcode', 'price', 'stock', 'min_stock', 'category', 'is_active']);

            Product::with('category')->orderBy('id')->chunk(200, function ($rows) use ($handle) {
                foreach ($rows as $p) {
                    fputcsv($handle, [
                        $p->id,
                        $p->name,
                        $p->sku,
                        $p->barcode,
                        $p->price,
                        $p->stock,
                        $p->min_stock,
                        $p->category->name ?? '',
                        $p->is_active ? '1' : '0',
                    ]);
                }
            });

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response;
    }
}
