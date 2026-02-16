<?php

namespace App\Http\Controllers;

use App\Mail\QuotationEmail;
use App\Models\Quotation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class QuotationController extends Controller
{
    public function index()
    {
        $query = DB::table('quotations')
            ->join('clients', 'quotations.client_id', '=', 'clients.id')
            ->join('users', 'quotations.created_by', '=', 'users.id')
            ->select('quotations.*', 
                    DB::raw("CONCAT(clients.first_name, ' ', clients.last_name) as client_name"), 
                    'users.name as created_by_name');
        
        // Apply status filter if provided
        if (request()->has('status') && request('status') !== null) {
            $query->where('quotations.status', request('status'));
        }
        
        // Apply search filter if provided
        if (request()->has('search') && !empty(request('search'))) {
            $searchTerm = '%' . request('search') . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('quotations.job_number', 'like', $searchTerm)
                  ->orWhere(DB::raw("CONCAT(clients.first_name, ' ', clients.last_name)"), 'like', $searchTerm)
                  ->orWhere('clients.email', 'like', $searchTerm);
            });
        }
        
        // Apply role-based filtering
        // Note: Technicians can now see all quotations (no filtering)
        // Admin can see all quotations, no filtering needed
        
        $quotations = $query->orderBy('quotations.created_at', 'desc')->paginate(15);
        
        return view('quotations.index', compact('quotations'));
    }

    public function create($job = null)
    {
        if ($job) {
            $jobData = DB::table('jobs')
                ->leftJoin('clients', 'jobs.client_id', '=', 'clients.id')
                ->select('jobs.*', 'clients.email as client_email', 'clients.phone as client_phone',
                        DB::raw("CONCAT(clients.first_name, ' ', clients.last_name) as client_name"))
                ->where('jobs.job_number', $job)
                ->first();
            
            if (!$jobData) {
                abort(404, 'Job not found');
            }

            //ENTERPRISE SAFETY CHECK
            if ($jobData->status !== 'Diagnosing') {
                abort(403, 'Quotation cannot be created at this stage');
            }

            // Check if quotation already exists for this job
            $existingQuotation = DB::table('quotations')
                ->where('job_number', $job)
                ->first();

            return view('quotations.create', compact('jobData', 'existingQuotation'));
        }
        
        return view('quotations.create');
    }

    public function createWithJob($job)
    {
        return $this->create($job);
    }

    public function show($id)
    {
        $quotation = DB::table('quotations')
            ->join('clients', 'quotations.client_id', '=', 'clients.id')
            ->join('users', 'quotations.created_by', '=', 'users.id')
            ->select('quotations.*', 
                    DB::raw("CONCAT(clients.first_name, ' ', clients.last_name) as client_name"), 
                    'users.name as created_by_name')
            ->where('quotations.id', $id)
            ->first();
        
        if (!$quotation) {
            abort(404);
        }
        
        $items = DB::table('quotation_items')
            ->where('quotation_id', $id)
            ->get();
        
        return view('quotations.show', compact('quotation', 'items'));
    }

    public function store(Request $request)
    {
        try {
            // Validate the request data
            $request->validate([
                'job_number' => 'required|string|exists:jobs,job_number',
                'client_id' => 'required|integer|exists:clients,id',
                'subtotal' => 'required|numeric|min:0',
                'tax' => 'required|numeric|min:0',
                'discount' => 'required|numeric|min:0',
                'total_amount' => 'required|numeric|min:0',
                'valid_until' => 'required|date|after:today',
                'items' => 'required|array|min:1',
                'items.*.description' => 'required|string',
                'items.*.quantity' => 'required|numeric|min:1',
                'items.*.unit_price' => 'required|numeric|min:0',
            ], [
                'valid_until.required' => 'The valid until date is required.',
                'valid_until.after' => 'The valid until date must be after today.',
                'items.required' => 'At least one item is required.',
                'items.*.description.required' => 'Item description is required.',
                'items.*.quantity.required' => 'Item quantity is required.',
                'items.*.quantity.min' => 'Item quantity must be at least 1.',
                'items.*.unit_price.required' => 'Item unit price is required.',
                'items.*.unit_price.min' => 'Item unit price must be at least 0.',
            ]);
            
            // Check if quotation already exists for this job
            $existingQuotation = DB::table('quotations')
                ->where('job_number', $request->job_number)
                ->first();
            
            if ($existingQuotation) {
                return redirect()->back()->with('error', 'A quotation request has already been sent for this job. Please wait for admin approval.');
            }
            
            $quotationId = null;
            
            DB::transaction(function () use ($request, &$quotationId) {
                $quotationData = [
                    'job_number' => $request->job_number,
                    'client_id' => $request->client_id,
                    'subtotal' => $request->subtotal,
                    'tax' => $request->tax,
                    'discount' => $request->discount,
                    'total_amount' => $request->total_amount,
                    'valid_until' => $request->valid_until, // Now always required
                    'status' => 'pending', // Changed to pending for technician requests
                    'created_by' => auth()->id(),
                ];
                
                $quotationId = DB::table('quotations')->insertGetId($quotationData);

                foreach ($request->items as $item) {
                    // Ensure unit_price exists (for technicians who don't see the field)
                    $unitPrice = isset($item['unit_price']) ? $item['unit_price'] : 0.00;
                    
                    DB::table('quotation_items')->insert([
                        'quotation_id' => $quotationId,
                        'description' => $item['description'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $unitPrice,
                        'total' => $item['quantity'] * $unitPrice,
                    ]);
                }
            });

            // Get the complete quotation with relationships
            $quotation = DB::table('quotations')
                ->leftJoin('clients', 'quotations.client_id', '=', 'clients.id')
                ->select('quotations.*', 'clients.first_name', 'clients.last_name', 'clients.email as client_email')
                ->where('quotations.id', $quotationId)
                ->first();

            // Handle role-specific email sending
            if (auth()->user()->role === 'technician') {
                // Send request to admins and storekeepers
                $this->sendQuotationRequestEmails($quotation);
                
                // Redirect technician to repair jobs list page
                return redirect()->route('jobs.index')->with('success', 'Quotation request sent to administrators and managers for approval!');
            } else {
                // Update quotation status to sent since admin is sending it immediately
                DB::table('quotations')
                    ->where('id', $quotationId)
                    ->update(['status' => 'sent']);
                
                // Send emails to client and admins (existing behavior)
                $quotation->status = 'sent'; // Update the object for email
                $this->sendQuotationEmails($quotation);
                return redirect()->route('quotations.index')->with('success', 'Quotation created successfully and sent to client and administrators!');
            }
            
        } catch (\Exception $e) {
            \Log::error('Quotation creation failed: ' . $e->getMessage());
            \Log::error('Request data: ' . json_encode($request->all()));
            \Log::error('User role: ' . auth()->user()->role);
            return redirect()->back()
                ->with('error', 'Failed to create quotation. Please try again.')
                ->withInput();
        }
    }

    private function sendQuotationRequestEmails($quotation)
    {
        try {
            // Send to all active administrators
            $admins = DB::table('users')->where('role', 'admin')->where('active', true)->get();
            foreach ($admins as $admin) {
                Mail::to($admin->email)
                    ->send(new QuotationEmail($quotation, 'admin_request'));
            }

            // Send to all active storekeepers (managers)
            $storekeepers = DB::table('users')->where('role', 'storekeeper')->where('active', true)->get();
            foreach ($storekeepers as $storekeeper) {
                Mail::to($storekeeper->email)
                    ->send(new QuotationEmail($quotation, 'manager_request'));
            }

        } catch (\Exception $e) {
            \Log::error('Failed to send quotation request emails: ' . $e->getMessage());
            // Don't fail the whole process if email fails
        }
    }

    private function sendQuotationEmails($quotation)
    {
        try {
            // Send to client if email exists
            if ($quotation->client_email) {
                Mail::to($quotation->client_email)
                    ->send(new QuotationEmail($quotation, 'client'));
            }

            // Send to all active administrators
            $admins = DB::table('users')->where('role', 'admin')->where('active', true)->get();
            foreach ($admins as $admin) {
                Mail::to($admin->email)
                    ->send(new QuotationEmail($quotation, 'admin'));
            }

            // Send to creator if not admin and is active
            $creator = DB::table('users')->find($quotation->created_by);
            if ($creator && $creator->role !== 'admin' && $creator->active) {
                Mail::to($creator->email)
                    ->send(new QuotationEmail($quotation, 'staff'));
            }

        } catch (\Exception $e) {
            \Log::error('Failed to send quotation emails: ' . $e->getMessage());
            // Don't fail the whole process if email fails
        }
    }

    public function sendEmail($id)
    {
        try {
            // Get the complete quotation with relationships
            $quotation = DB::table('quotations')
                ->leftJoin('clients', 'quotations.client_id', '=', 'clients.id')
                ->select('quotations.*', 'clients.first_name', 'clients.last_name', 'clients.email as client_email')
                ->where('quotations.id', $id)
                ->first();

            if (!$quotation) {
                return redirect()->back()->with('error', 'Quotation not found.');
            }

            // Check if quotation is in pending status (only pending quotations can be sent)
            if ($quotation->status !== 'pending') {
                return redirect()->back()->with('error', 'This quotation has already been processed and cannot be sent again.');
            }

            // Validate that all items have valid prices (not null, 0, or negative)
            $items = DB::table('quotation_items')
                ->where('quotation_id', $id)
                ->get();

            foreach ($items as $item) {
                if (is_null($item->unit_price) || $item->unit_price <= 0) {
                    return redirect()->back()->with('error', 'All items must have valid unit prices greater than 0 before sending the quotation. Item "' . $item->description . '" has an invalid price.');
                }
            }

            // Validate total amount (not null, 0, or negative)
            if (is_null($quotation->total_amount) || $quotation->total_amount <= 0) {
                return redirect()->back()->with('error', 'Quotation total must be greater than 0 before sending.');
            }

            // Update quotation status to sent
            DB::table('quotations')
                ->where('id', $id)
                ->update(['status' => 'sent']);

            // Send emails
            $quotation->status = 'sent'; // Update object for email
            $this->sendQuotationEmails($quotation);

            // Redirect admins to quotations list, technicians stay on same page
            if (auth()->user()->role === 'technician') {
                return redirect()->back()->with('success', 'Quotation email sent successfully to client and administrators!');
            } else {
                return redirect()->route('quotations.index')->with('success', 'Quotation email sent successfully to client and administrators!');
            }

        } catch (\Exception $e) {
            \Log::error('Failed to send quotation email: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to send quotation email. Please try again.');
        }
    }

    public function acceptQuotation($id)
    {
        try {
            // Update quotation status
            $updated = DB::table('quotations')
                ->where('id', $id)
                ->where('status', 'sent') // Only accept if currently sent
                ->update(['status' => 'accepted']);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Quotation not found or cannot be accepted'
                ], 404);
            }

            // Get quotation details for job update
            $quotation = DB::table('quotations')
                ->where('id', $id)
                ->first();

            // Update job status to In Progress
            if ($quotation) {
                DB::table('jobs')
                    ->where('job_number', $quotation->job_number)
                    ->update(['status' => 'In Progress']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Quotation accepted successfully!'
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to accept quotation: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to accept quotation. Please try again.'
            ], 500);
        }
    }

    public function sendQuotation($id)
    {
        try {
            // Update quotation status from pending to sent
            $updated = DB::table('quotations')
                ->where('id', $id)
                ->where('status', 'pending') // Only send if currently pending
                ->update(['status' => 'sent']);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Quotation not found or has already been sent'
                ], 404);
            }

            // Get quotation details for email
            $quotation = DB::table('quotations')
                ->leftJoin('clients', 'quotations.client_id', '=', 'clients.id')
                ->select('quotations.*', 'clients.first_name', 'clients.last_name', 'clients.email as client_email')
                ->where('quotations.id', $id)
                ->first();

            // Send emails to client and admins
            if ($quotation) {
                $this->sendQuotationEmails($quotation);
            }

            return response()->json([
                'success' => true,
                'message' => 'Quotation sent to client successfully!'
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to send quotation: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send quotation. Please try again.'
            ], 500);
        }
    }

    public function updatePrices(Request $request, $id)
    {
        try {
            // Only allow admins and managers to update prices
            if (auth()->user()->role === 'technician') {
                return redirect()->back()->with('error', 'You are not authorized to update prices.');
            }

            // Validate the request data
            $request->validate([
                'items' => 'required|array',
                'items.*.id' => 'required|integer|exists:quotation_items,id',
                'items.*.unit_price' => 'required|numeric|min:0', // Allow 0 for saving
                'subtotal' => 'required|numeric|min:0',
                'tax' => 'required|numeric|min:0',
                'total_amount' => 'required|numeric|min:0',
            ]);

            DB::transaction(function () use ($request, $id) {
                // Log the values being saved
                \Log::info('Updating quotation totals:', [
                    'subtotal' => $request->subtotal,
                    'tax' => $request->tax,
                    'total_amount' => $request->total_amount,
                    'quotation_id' => $id
                ]);
                
                // Update quotation totals
                DB::table('quotations')
                    ->where('id', $id)
                    ->update([
                        'subtotal' => $request->subtotal,
                        'tax' => $request->tax,
                        'total_amount' => $request->total_amount,
                    ]);

                // Update quotation items
                foreach ($request->items as $index => $item) {
                    if (isset($item['id']) && isset($item['unit_price'])) {
                        $unitPrice = $item['unit_price'];
                        $itemId = $item['id'];
                        
                        // Get the current item to get quantity
                        $quotationItem = DB::table('quotation_items')
                            ->where('id', $itemId)
                            ->where('quotation_id', $id)
                            ->first();
                            
                        if ($quotationItem) {
                            $quantity = $quotationItem->quantity;
                            $itemSubtotal = $quantity * $unitPrice; // quantity * unit price
                            $itemTax = $itemSubtotal * 0.16; // 16% tax on subtotal
                            $finalTotal = $itemSubtotal + $itemTax; // subtotal + tax

                            \Log::info('Updating item:', [
                                'item_id' => $itemId,
                                'unit_price' => $unitPrice,
                                'quantity' => $quantity,
                                'item_total' => $finalTotal
                            ]);

                            DB::table('quotation_items')
                                ->where('id', $itemId)
                                ->update([
                                    'unit_price' => $unitPrice,
                                    'total' => $finalTotal, // Save final total including tax
                                ]);
                        }
                    }
                }
            });

            return redirect()->route('quotations.index')->with('success', 'Quotation prices updated successfully!');

        } catch (\Exception $e) {
            \Log::error('Failed to update quotation prices: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update prices. Please try again.');
        }
    }
}
