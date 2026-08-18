<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::withSum(
            ['stockMovements as stock_in' => fn($q) => $q->where('movement_type', 'IN')],
            'quantity'
        )->withSum(
            ['stockMovements as stock_out' => fn($q) => $q->where('movement_type', 'OUT')],
            'quantity'
        )->paginate(15)->through(function ($product) {
            $product->current_stock = ($product->stock_in ?? 0) - ($product->stock_out ?? 0);
            $product->is_low = $product->current_stock <= $product->minimum_stock;
            return $product;
        });

        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'minimum_stock' => 'required|integer|min:0',
        ]);

        Product::create($request->only(['name', 'category', 'description', 'minimum_stock']));

        return redirect()->route('products.index')->with('success', 'Product created successfully');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'minimum_stock' => 'required|integer|min:0',
        ]);

        $product->update($request->only(['name', 'category', 'description', 'minimum_stock']));

        return redirect()->route('products.index')->with('success', 'Product updated successfully');
    }
}
