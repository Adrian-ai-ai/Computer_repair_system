<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Job;
use App\Models\JobAccessory;
use App\Models\JobStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\ActivityLog;

class JobController extends Controller
{
    // Show jobs list
    public function index(Request $request)
    {
        // Get all jobs with search functionality
        $query = Job::with('client')->orderBy('received_at', 'desc');
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('job_number', 'like', '%' . $search . '%')
                  ->orWhere('serial_number', 'like', '%' . $search . '%')
                  ->orWhereRaw('LOWER(status) LIKE ?', ['%' . strtolower($search) . '%']);
            });
        }
        
        $jobs = $query->paginate(10);
        return view('jobs.index', compact('jobs'));
    }

    // Show intake form
    public function create()
    {
        return view('jobs.create');
    }

    // Generate sequential job number starting from 3901
    private function generateJobNumber()
    {
        $lastJob = Job::orderBy('id', 'desc')->first();
        
        if (!$lastJob) {
            return '3901'; // Start from 3901
        }
        
        // Check if last job has numeric format
        if (is_numeric($lastJob->job_number)) {
            return (string)((int)$lastJob->job_number + 1);
        }
        
        // For existing random format jobs, start from 3901
        return '3901';
    }

    // Store new repair job
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'device_type' => 'required|in:Computer,Printer',
            'fault_description' => 'required',
            'warranty_status' => 'required|in:In Warranty,Out of Warranty,Unknown',
            'warranty_expiry_date' => 'nullable|date',
            'accessories' => 'nullable|array',
            'accessories.*.name' => 'required_with:accessories|string|max:255',
            'accessories.*.description' => 'nullable|string',
            'accessories.*.quantity' => 'required_with:accessories|integer|min:1'
        ]);

        // Create or reuse client
        $client = Client::firstOrCreate(
            ['phone' => $request->phone],
            $request->only('first_name','last_name','email')
        );

        // Create job
        $job = Job::create([
            'job_number' => $this->generateJobNumber(),
            // 'job_number' => 'JOB-' . strtoupper(Str::random(6)), // Original method - kept for backup
            'client_id' => $client->id,
            'device_type' => $request->device_type,
            'brand' => $request->brand,
            'model' => $request->model,
            'serial_number' => $request->serial_number,
            'fault_description' => $request->fault_description,
            'status' => 'Received',
            'warranty_status' => $request->warranty_status,
            'warranty_expiry_date' => $request->warranty_expiry_date,
            'received_by' => Auth::id(),
            'received_at' => now(),
            'technician_id' => Auth::user()->role === 'technician' ? Auth::id() : null
        ]);

        // Create accessories if provided
        if ($request->has('accessories') && is_array($request->accessories)) {
            foreach ($request->accessories as $accessoryData) {
                if (!empty($accessoryData['name'])) {
                    JobAccessory::create([
                        'job_id' => $job->id,
                        'name' => $accessoryData['name'],
                        'description' => $accessoryData['description'] ?? null,
                        'quantity' => $accessoryData['quantity'] ?? 1
                    ]);
                }
            }
        }

        // Initial status history
        JobStatusHistory::create([
            'job_id' => $job->id,
            'status' => 'Received',
            'changed_by' => Auth::id(),
            'changed_at' => now()
        ]);

        return redirect()->route('jobs.index')->with('success', 'Repair job created successfully');
    }

    // View job
    public function show(Job $job)
    {
        $job->load(['statusHistory.user', 'client', 'receiver', 'accessories', 'partsUsed']);
        return view('jobs.show', compact('job'));
    }

    // Show add-part form
    public function addPartForm(Job $job)
    {
        $products = Product::all();
        return view('jobs.add-part', compact('job','products'));
    }

    // Store part usage
    public function storePart(Request $request, Job $job)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity_used' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request, $job) {

            $product = Product::findOrFail($request->product_id);

            // Calculate available stock
            $stockIn = $product->stockMovements()->where('movement_type','IN')->sum('quantity');
            $stockOut = $product->stockMovements()->where('movement_type','OUT')->sum('quantity');
            $availableStock = $stockIn - $stockOut;

            if ($request->quantity_used > $availableStock) {
                abort(400, 'Not enough stock available');
            }

            // Record parts used
            $job->partsUsed()->attach($product->id, [
                'quantity_used' => $request->quantity_used,
                'is_warranty' => $request->is_warranty ?? false
            ]);

            // Deduct stock
            StockMovement::create([
                'product_id' => $product->id,
                'movement_type' => 'OUT',
                'quantity' => $request->quantity_used,
                'reference_type' => 'repair',
                'reference_id' => $job->id,
                'created_by' => auth()->id()
            ]);

            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'Stock OUT for repair job #' . $job->job_number,
                'created_at' => now(),
            ]);

        });

        return redirect()->route('jobs.show', $job)
            ->with('success', 'Part added and stock deducted successfully');
    }

    public function updateStatus(Request $request, Job $job)
    {
        $request->validate([
            'status' => 'required|string'
        ]);

        if (!$job->canMoveTo($request->status)) {
            return back()->withErrors('Invalid status transition');
        }

        DB::transaction(function () use ($job, $request) {

            $job->update([
                'status' => $request->status
            ]);

            JobStatusHistory::create([
                'job_id' => $job->id,
                'status' => $request->status,
                'changed_by' => Auth::id(),
                'changed_at' => now()
            ]);
        });

        return back()->with('success', 'Job status updated successfully');
    }
}