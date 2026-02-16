<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    // Show products list
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

        return view('inventory.index', compact('products'));
    }

    // Show add product form
    public function createProduct()
    {
        return view('inventory.create-product');
    }

    // Store product
    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'category' => 'nullable|string',
            'description' => 'nullable|string',
            'minimum_stock' => 'required|integer|min:0'

        ]);

        Product::create($request->only('name','category','description','minimum_stock'));

        return redirect()->route('inventory.index')->with('success', 'Product added successfully');
    }

    // Stock IN / OUT form
    public function stockForm(Product $product)
    {
        return view('inventory.stock', compact('product'));
    }

    // Process stock movement
    public function stockMovement(Request $request, Product $product)
    {
        $request->validate([
            'movement_type' => 'required|in:IN,OUT',
            'quantity' => 'required|integer|min:1'
        ]);

        DB::transaction(function () use ($request, $product) {

            StockMovement::create([
                'product_id' => $product->id,
                'movement_type' => $request->movement_type,
                'quantity' => $request->quantity,
                'reference_type' => 'manual',
                'created_by' => Auth::id()
            ]);
        });

        return redirect()->route('inventory.index')->with('success', 'Stock updated successfully');
    }
}
