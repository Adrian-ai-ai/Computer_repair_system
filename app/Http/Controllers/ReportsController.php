<?php

namespace App\Http\Controllers;

use App\Mail\JobStatusReport;
use App\Models\Job;
use App\Models\User;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportsController extends Controller
{
     public function generateClientReport(Client $client, $dateFrom = null, $dateTo = null)
{
    $jobs = $this->getJobsForClient($client->id, $dateFrom, $dateTo);

    $totalJobs = $jobs->count();
    $completedJobs = $jobs->where('status', 'Delivered')->count();

    $completionRate = $totalJobs > 0
        ? round(($completedJobs / $totalJobs) * 100, 2)
        : 0;

    $statusBreakdown = $jobs
        ->groupBy('status')
        ->map(fn ($group) => $group->count());

    return [
        'client' => $client,
        'total_jobs' => $totalJobs,
        'jobs_in_period' => $jobs,
        'completion_rate' => $completionRate,
        'status_breakdown' => $statusBreakdown,
    ];
}

    public function warrantyPartsUsage()
    {
        $warrantyParts = DB::table('job_parts_used')
            ->join('products', 'products.id', '=', 'job_parts_used.product_id')
            ->where('job_parts_used.is_warranty', true)
            ->select(
                'products.name',
                DB::raw('SUM(job_parts_used.quantity_used) as total_used')
            )
            ->groupBy('products.name')
            ->orderBy('total_used', 'desc')
            ->paginate(15);

        return view('reports.warranty-parts', compact('warrantyParts'));
        // or return response()->json($warrantyParts);
    }

    /**
     * Show the reports dashboard
     */
    public function dashboard()
    {
        return view('reports.dashboard');
    }

    /**
     * Show activity summary report
     */
    public function activity()
    {
        // Get basic statistics
        $totalJobs = Job::count();
        $completedJobs = Job::where('status', 'Delivered')->count();
        $activeJobs = Job::whereIn('status', ['Received', 'Diagnosing', 'Waiting for parts', 'Repairing'])->count();
        $totalClients = Client::count();
        
        // Get jobs by status
        $jobsByStatus = Job::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->orderBy('total', 'desc')
            ->get();
        
        // Get recent jobs (last 7 days)
        $recentJobs = Job::with('client')
            ->where('received_at', '>=', Carbon::now()->subDays(7))
            ->orderBy('received_at', 'desc')
            ->limit(10)
            ->get();
        
        // Get monthly job trends (last 6 months)
        $monthlyTrends = Job::select(
                DB::raw('DATE_TRUNC(\'month\', received_at) as month'),
                DB::raw('count(*) as total')
            )
            ->where('received_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        return view('reports.activity', compact(
            'totalJobs',
            'completedJobs', 
            'activeJobs',
            'totalClients',
            'jobsByStatus',
            'recentJobs',
            'monthlyTrends'
        ));
    }

    /**
     * Show the email reports page
     */
    public function email()
    {
        $clients = Client::select('id', 'first_name', 'last_name', 'email')->get();
        return view('reports.email', compact('clients'));
    }

    /**
     * Show the inventory email reports page
     */
    public function inventoryEmail()
    {
        $staff = User::where('role', 'admin')->orWhere('role', 'storekeeper')->get();
        return view('reports.inventory-email', compact('staff'));
    }

    /**
     * Send inventory report via email
     */
    public function sendInventoryReport(Request $request)
    {
        $request->validate([
            'recipients' => 'required|array',
            'recipients.*' => 'exists:users,id',
            'subject' => 'required|string|max:255',
            'message' => 'nullable|string'
        ]);

        try {
            // Get inventory data
            $inventoryData = $this->getInventoryReportData();
            
            // Send to each recipient
            foreach ($request->recipients as $recipientId) {
                $recipient = User::find($recipientId);
                
                Mail::to($recipient->email)->send(new \App\Mail\InventoryReport($inventoryData, $request->subject, $request->message));
            }

            return back()->with('success', 'Inventory report sent successfully to ' . count($request->recipients) . ' recipients.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send inventory report: ' . $e->getMessage());
        }
    }

    /**
     * Show the products email reports page
     */
    public function productsEmail()
    {
        $staff = User::where('role', 'admin')->orWhere('role', 'storekeeper')->get();
        return view('reports.products-email', compact('staff'));
    }

    /**
     * Send products report via email
     */
    public function sendProductsReport(Request $request)
    {
        $request->validate([
            'recipients' => 'required|array',
            'recipients.*' => 'exists:users,id',
            'subject' => 'required|string|max:255',
            'message' => 'nullable|string'
        ]);

        try {
            // Get products data
            $productsData = $this->getProductsReportData();
            
            // Send to each recipient
            foreach ($request->recipients as $recipientId) {
                $recipient = User::find($recipientId);
                
                Mail::to($recipient->email)->send(new \App\Mail\ProductsReport($productsData, $request->subject, $request->message));
            }

            return back()->with('success', 'Products report sent successfully to ' . count($request->recipients) . ' recipients.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send products report: ' . $e->getMessage());
        }
    }

    /**
     * Show technician jobs report
     */
    public function technicianJobs()
    {
        $technicianJobs = Job::where('technician_id', Auth::id())
            ->with('client')
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'total' => $technicianJobs->count(),
            'completed' => $technicianJobs->where('status', 'Delivered')->count(),
            'in_progress' => $technicianJobs->whereIn('status', ['Diagnosing', 'Repairing', 'Testing'])->count(),
            'waiting_parts' => $technicianJobs->where('status', 'Waiting for parts')->count(),
        ];

        $stats['completion_rate'] = $stats['total'] > 0 ? round(($stats['completed'] / $stats['total']) * 100, 2) : 0;

        return view('reports.technician-jobs', compact('technicianJobs', 'stats'));
    }

    /**
     * Get inventory report data
     */
    private function getInventoryReportData()
    {
        return [
            'products' => DB::table('products')
                ->leftJoin('stock_movements', 'products.id', '=', 'stock_movements.product_id')
                ->select(
                    'products.*',
                    DB::raw('COALESCE(SUM(CASE WHEN stock_movements.type = "in" THEN stock_movements.quantity ELSE 0 END), 0) as total_in'),
                    DB::raw('COALESCE(SUM(CASE WHEN stock_movements.type = "out" THEN stock_movements.quantity ELSE 0 END), 0) as total_out'),
                    DB::raw('COALESCE(products.current_stock, 0) as current_stock')
                )
                ->groupBy('products.id')
                ->orderBy('products.name')
                ->get(),
            'low_stock' => DB::table('products')
                ->where('current_stock', '<=', DB::raw('COALESCE(min_stock_level, 10)'))
                ->count(),
            'total_value' => DB::table('products')
                ->select(DB::raw('SUM(current_stock * COALESCE(unit_price, 0)) as total'))
                ->first()
                ->total,
            'movements_today' => DB::table('stock_movements')
                ->whereDate('created_at', now())
                ->count(),
        ];
    }

    /**
     * Get products report data
     */
    private function getProductsReportData()
    {
        return [
            'products' => DB::table('products')->orderBy('name')->get(),
            'total_products' => DB::table('products')->count(),
            'active_products' => DB::table('products')->where('is_active', true)->count(),
            'categories' => DB::table('products')
                ->select('category', DB::raw('count(*) as count'))
                ->whereNotNull('category')
                ->groupBy('category')
                ->orderBy('count', 'desc')
                ->get(),
            'usage_stats' => DB::table('job_parts_used')
                ->join('products', 'job_parts_used.product_id', '=', 'products.id')
                ->select('products.name', 'products.category', DB::raw('COUNT(*) as usage_count'), DB::raw('SUM(job_parts_used.quantity) as total_quantity'))
                ->groupBy('products.id', 'products.name', 'products.category')
                ->orderBy('usage_count', 'desc')
                ->limit(10)
                ->get(),
        ];
    }

    /**
     * Send job status report to a specific client
     */
    public function sendToClient(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
        ]);

        $client = Client::findOrFail($request->client_id);
        $jobs = $this->getJobsForClient($client->id, $request->date_from, $request->date_to);

        $recipient = [
            'type' => 'client',
            'name' => $client->first_name . ' ' . $client->last_name,
            'email' => $client->email,
        ];

        Mail::to($client->email)->send(new JobStatusReport(
            $jobs,
            'custom',
            $recipient,
            Auth::user(),
            $this->getDateRange($request->date_from, $request->date_to)
        ));

        return back()->with('success', 'Report sent successfully to ' . $recipient['name']);
    }
    

    /**
     * Send job status report to all staff
     */
    public function sendToStaff(Request $request)
    {
        $request->validate([
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
        ]);

        // Send to active staff only
        $staff = User::whereIn('role', ['technician', 'storekeeper'])->where('active', true)->get();
        $jobs = $this->getAllJobs($request->date_from, $request->date_to);

        $recipient = [
            'type' => 'staff',
            'count' => $staff->count(),
        ];

        foreach ($staff as $user) {
            Mail::to($user->email)->send(new JobStatusReport(
                $jobs,
                'custom',
                $recipient,
                Auth::user(),
                $this->getDateRange($request->date_from, $request->date_to)
            ));
        }

        return back()->with('success', 'Report sent successfully to ' . $staff->count() . ' staff members');
    }

    /**
     * Send job status report to managers/administrators
     */
    public function sendToManagers(Request $request)
    {
        $request->validate([
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
        ]);

        // Send to active managers only
        $managers = User::where('role', 'admin')->where('active', true)->get();
        $jobs = $this->getAllJobs($request->date_from, $request->date_to);

        $recipient = [
            'type' => 'manager',
            'count' => $managers->count(),
        ];

        foreach ($managers as $user) {
            Mail::to($user->email)->send(new JobStatusReport(
                $jobs,
                'custom',
                $recipient,
                Auth::user(),
                $this->getDateRange($request->date_from, $request->date_to)
            ));
        }

        return back()->with('success', 'Report sent successfully to ' . $managers->count() . ' managers');
    }

    /**
     * Send daily report to all relevant recipients
     */
    public function sendDailyReport()
    {
        $yesterday = Carbon::yesterday()->setTimezone(config('app.timezone'));
        $jobs = $this->getJobsForPeriod($yesterday->startOfDay(), $yesterday->endOfDay());

        // Send to active managers
        $managers = User::where('role', 'admin')->where('active', true)->get();
        foreach ($managers as $manager) {
            Mail::to($manager->email)->send(new JobStatusReport(
                $jobs,
                'daily',
                ['type' => 'manager'],
                null,
                [$yesterday->startOfDay(), $yesterday->endOfDay()]
            ));
        }

        // Send to active staff
        $staff = User::whereIn('role', ['technician', 'storekeeper'])->where('active', true)->get();
        foreach ($staff as $user) {
            Mail::to($user->email)->send(new JobStatusReport(
                $jobs,
                'daily',
                ['type' => 'staff'],
                null,
                [$yesterday->startOfDay(), $yesterday->endOfDay()]
            ));
        }

        return response()->json(['message' => 'Daily reports sent successfully']);
    }

    /**
     * Send weekly report to managers
     */
    public function sendWeeklyReport()
    {
        $lastWeek = Carbon::now()->subWeek()->setTimezone(config('app.timezone'));
        $jobs = $this->getJobsForPeriod($lastWeek->startOfWeek(), $lastWeek->endOfWeek());

        // Send to active managers only
        $managers = User::where('role', 'admin')->where('active', true)->get();
        foreach ($managers as $manager) {
            Mail::to($manager->email)->send(new JobStatusReport(
                $jobs,
                'weekly',
                ['type' => 'manager'],
                null,
                [$lastWeek->startOfWeek(), $lastWeek->endOfWeek()]
            ));
        }

        return response()->json(['message' => 'Weekly reports sent successfully']);
    }

    /**
     * Get jobs for a specific client
     */
    private function getJobsForClient($clientId, $dateFrom = null, $dateTo = null)
    {
        $query = Job::with(['client', 'receiver', 'statusHistory.user', 'quotations'])
                   ->where('client_id', $clientId);

        if ($dateFrom && $dateTo) {
            $query->whereBetween('created_at', [$dateFrom, $dateTo]);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Get all jobs with optional date filter
     */
    private function getAllJobs($dateFrom = null, $dateTo = null)
    {
        $query = Job::with(['client', 'receiver', 'statusHistory.user']);

        if ($dateFrom && $dateTo) {
            $query->whereBetween('created_at', [$dateFrom, $dateTo]);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Get jobs for a specific date period
     */
    private function getJobsForPeriod($startDate, $endDate)
    {
        return Job::with(['client', 'receiver', 'statusHistory.user'])
                 ->whereBetween('created_at', [$startDate, $endDate])
                 ->orderBy('created_at', 'desc')
                 ->get();
    }

    /**
     * Format date range for reports
     */
    private function getDateRange($dateFrom, $dateTo)
    {
        if ($dateFrom && $dateTo) {
            return [Carbon::parse($dateFrom), Carbon::parse($dateTo)];
        }
        return null;
    }
}