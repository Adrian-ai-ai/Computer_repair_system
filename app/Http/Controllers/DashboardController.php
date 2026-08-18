<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Job;
use App\Models\StockMovement;
use App\Models\Client;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Inventory summary with stock calculations
        $inventory = Product::withSum(
            ['stockMovements as stock_in' => fn($q) => $q->where('movement_type','IN')],
            'quantity'
        )->withSum(
            ['stockMovements as stock_out' => fn($q) => $q->where('movement_type','OUT')],
            'quantity'
        )->get()->map(function ($product) {
            $product->current_stock = ($product->stock_in ?? 0) - ($product->stock_out ?? 0);
            $product->is_low = $product->current_stock <= $product->minimum_stock;
            return $product;
        });

        // Repair jobs by status
        $jobsByStatus = Job::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        // Warranty summary
        $warrantyJobs = Job::where('is_under_warranty', true)->count();
        $paidJobs = Job::where('is_under_warranty', false)->count();

        // Additional statistics for dashboard cards
        $totalJobs = Job::count();
        $activeJobs = Job::whereNotIn('status', ['Delivered', 'Cancelled'])->count();
        $lowStockCount = $inventory->where('is_low', true)->count();
        $totalClients = Client::count();

        return view('dashboard.index', compact(
            'inventory',
            'jobsByStatus',
            'warrantyJobs',
            'paidJobs',
            'totalJobs',
            'activeJobs',
            'lowStockCount',
            'totalClients'
        ));
    }


}
