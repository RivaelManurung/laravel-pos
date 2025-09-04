<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function index()
    {
        $customers = Customer::withCount('orders')->paginate(10);
        return view('admin.customers.index', compact('customers'));
    }

    public function create()
    {
        return view('admin.customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string'
        ]);

        Customer::create($validated);

        return redirect()->route('admin.customers.index')
            ->with('success', 'Pelanggan berhasil dibuat.');
    }

    public function show(Customer $customer)
    {
        $customer->load('orders');
        return view('admin.customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string'
        ]);

        $customer->update($validated);

        return redirect()->route('admin.customers.index')
            ->with('success', 'Pelanggan berhasil diupdate.');
    }

    public function destroy(Customer $customer)
    {
        if ($customer->orders()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Tidak bisa menghapus pelanggan yang memiliki transaksi.');
        }

        $customer->delete();

        return redirect()->back()
            ->with('success', 'Pelanggan berhasil dihapus.');
    }
}