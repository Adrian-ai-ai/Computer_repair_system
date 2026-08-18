<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Quotations
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Header Section -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Quotations</h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage and view all quotation records</p>
                    </div>

                    <!-- Search and Filter -->
                    <div class="mb-6">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div class="flex-1 max-w-md">
                                <form method="GET" action="{{ route('quotations.index') }}">
                                    <div class="flex">
                                        <input type="hidden" name="status" value="{{ request('status') }}">
                                        <input type="text" name="search" 
                                               class="block w-full rounded-l-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" 
                                               placeholder="Search by job number, client..." 
                                               value="{{ request('search') }}">
                                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-l-0 border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-300 rounded-r-md hover:bg-gray-100 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="flex items-center space-x-3">
                                <!-- Clear Filters Button -->
                                @if(request('status') || request('search'))
                                <a href="{{ route('quotations.index') }}" class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    Clear Filters
                                </a>
                                @endif
                                <!-- Filter Dropdown -->
                                <div class="relative">
                                    <button type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                            onclick="document.getElementById('filterDropdown').classList.toggle('hidden')">
                                        @if(request('status'))
                                            Status: {{ ucfirst(request('status')) }}
                                        @else
                                            Filter by Status
                                        @endif
                                        <svg class="ml-2 -mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>
                                    <div id="filterDropdown" class="hidden absolute right-0 z-10 mt-2 mr-8 w-full bg-white dark:bg-gray-700 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 border border-gray-200 dark:border-gray-600">
                                        <div class="py-2 text-center">
                                            <a href="{{ request()->fullUrlWithQuery(['status' => null, 'page' => null]) }}" 
                                               class="block px-4 py-2 text-sm {{ !request('status') ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300' }} hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-150">
                                                All
                                            </a>
                                            <a href="{{ request()->fullUrlWithQuery(['status' => 'pending', 'page' => null]) }}" 
                                               class="block px-4 py-2 text-sm {{ request('status') == 'pending' ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300' }} hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-150">
                                                Pending
                                            </a>
                                            <a href="{{ request()->fullUrlWithQuery(['status' => 'sent', 'page' => null]) }}" 
                                               class="block px-4 py-2 text-sm {{ request('status') == 'sent' ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300' }} hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-150">
                                                Sent
                                            </a>
                                            <a href="{{ request()->fullUrlWithQuery(['status' => 'accepted', 'page' => null]) }}" 
                                               class="block px-4 py-2 text-sm {{ request('status') == 'accepted' ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300' }} hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-150">
                                                Accepted
                                            </a>
                                            <a href="{{ request()->fullUrlWithQuery(['status' => 'rejected', 'page' => null]) }}" 
                                               class="block px-4 py-2 text-sm {{ request('status') == 'rejected' ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300' }} hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-150">
                                                Rejected
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filter Status Indicator -->
                    @if(request('status') || request('search'))
                    <div class="mb-4 p-3 bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-md">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-blue-700 dark:text-blue-300">
                                <strong>Active Filters:</strong>
                                @if(request('status'))
                                    <span class="ml-2 px-2 py-1 bg-blue-100 dark:bg-blue-800 text-blue-800 dark:text-blue-200 rounded-full text-xs">
                                        Status: {{ ucfirst(request('status')) }}
                                    </span>
                                @endif
                                @if(request('search'))
                                    <span class="ml-2 px-2 py-1 bg-blue-100 dark:bg-blue-800 text-blue-800 dark:text-blue-200 rounded-full text-xs">
                                        Search: "{{ request('search') }}"
                                    </span>
                                @endif
                            </div>
                            <a href="{{ route('quotations.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-200">
                                Clear All
                            </a>
                        </div>
                    </div>
                    @endif

                    <!-- Quotations Table -->
                    @if($quotations->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Job Number</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Client</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Valid Until</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Created By</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Created At</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($quotations as $quotation)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $quotation->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('jobs.show', $quotation->job_number) }}" 
                                               class="text-sm text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                {{ $quotation->job_number }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $quotation->client_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">ZMW{{ number_format($quotation->total_amount, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($quotation->status == 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                @elseif($quotation->status == 'sent') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                @elseif($quotation->status == 'accepted') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                                {{ ucfirst($quotation->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            @php
                                                $validUntil = $quotation->valid_until ? (is_string($quotation->valid_until) ? \Carbon\Carbon::parse($quotation->valid_until) : $quotation->valid_until) : null;
                                            @endphp
                                            @if($validUntil)
                                                {{ $validUntil->format('M d, Y') }}
                                                @if($validUntil->isPast())
                                                    <span class="ml-2 text-red-600 dark:text-red-400">
                                                        <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                                        </svg>
                                                    </span>
                                                @endif
                                            @else
                                                <span class="text-gray-400 dark:text-gray-500">No expiry date</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $quotation->created_by_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            @php
                                                $createdAt = is_string($quotation->created_at) ? \Carbon\Carbon::parse($quotation->created_at) : $quotation->created_at;
                                            @endphp
                                            {{ $createdAt->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('quotations.show', $quotation->id) }}" 
                                                   class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300" title="View">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </a>
                                                <button class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300" 
                                                        onclick="printQuotation({{ $quotation->id }})" title="Print">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                                    </svg>
                                                </button>
                                                @if($quotation->status == 'sent' && auth()->user()->role !== 'technician')
                                                <button class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300" 
                                                        onclick="acceptQuotation({{ $quotation->id }})" title="Accept">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </button>
<<<<<<< HEAD
=======
                                                <button class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" 
                                                        onclick="rejectQuotation({{ $quotation->id }})" title="Reject">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
>>>>>>> 814c0e3a080a93b0a4c40958610f5493345a9fd8
                                                @endif
                                                @if($quotation->status == 'pending' && auth()->user()->role !== 'technician')
                                                <button class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300" 
                                                        onclick="sendQuotation({{ $quotation->id }})" title="Send to Client">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                                    </svg>
                                                </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6 flex justify-center">
                            {{ $quotations->links() }}
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No quotations found</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating a quotation for a job in "Diagnosing" status.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
function printQuotation(quotationId) {
    window.open(`{{ route('quotations.show', ':id') }}`.replace(':id', quotationId) + '?print=1', '_blank');
}

function acceptQuotation(quotationId) {
    if (confirm('Are you sure you want to accept this quotation?')) {
        fetch(`/quotations/${quotationId}/accept`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error accepting quotation: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error accepting quotation');
        });
    }
}

<<<<<<< HEAD
=======
function rejectQuotation(quotationId) {
    if (confirm('Are you sure you want to reject this quotation? This action cannot be undone.')) {
        fetch(`/quotations/${quotationId}/reject`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error rejecting quotation: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error rejecting quotation');
        });
    }
}

>>>>>>> 814c0e3a080a93b0a4c40958610f5493345a9fd8
// Auto-refresh quotation statuses every 30 seconds for real-time updates
function checkQuotationStatuses() {
    // Show subtle loading indicator
    const statusIndicator = document.createElement('div');
    statusIndicator.id = 'status-update-indicator';
    statusIndicator.style.cssText = 'position: fixed; top: 10px; right: 10px; background: #3B82F6; color: white; padding: 5px 10px; border-radius: 4px; font-size: 12px; z-index: 9999;';
    statusIndicator.textContent = 'Updating statuses...';
    document.body.appendChild(statusIndicator);
    
    fetch(window.location.pathname + window.location.search, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        // Create a temporary DOM element to parse the response
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = html;
        
        // Find the table body in the response and current page
        const newTableBody = tempDiv.querySelector('tbody');
        const currentTableBody = document.querySelector('tbody');
        
        if (newTableBody && currentTableBody) {
            // Replace the current table body with the updated one
            currentTableBody.innerHTML = newTableBody.innerHTML;
        }
        
        // Remove the indicator
        const indicator = document.getElementById('status-update-indicator');
        if (indicator) {
            indicator.remove();
        }
    })
    .catch(error => {
        console.log('Status check failed:', error);
        // Remove the indicator even on error
        const indicator = document.getElementById('status-update-indicator');
        if (indicator) {
            indicator.remove();
        }
    });
}

// Start the status checking when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Check for status updates every 30 seconds
    setInterval(checkQuotationStatuses, 30000);
});

function sendQuotation(quotationId) {
    if (confirm('Are you sure you want to send this quotation to the client?')) {
        fetch(`/quotations/${quotationId}/send`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error sending quotation: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error sending quotation');
        });
    }
}
</script>
