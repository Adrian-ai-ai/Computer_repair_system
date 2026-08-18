<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Repair Jobs Management') }}
            </h2>
            <x-back-button href="{{ route('dashboard') }}" />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Repair Jobs</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Track and manage all repair jobs in your system</p>
                        </div>
                        <a href="{{ route('jobs.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-150 ease-in-out">
                            New Repair Job
                        </a>
                    </div>

                    <!-- Search Bar -->
                    <div class="mb-6">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div class="flex-1 max-w-md">
                                <form method="GET" action="{{ route('jobs.index') }}">
                                    <div class="flex">
                                        <input type="text" name="search" 
                                               class="block w-full rounded-l-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" 
                                               placeholder="Search by job number, serial number or status..." 
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
                                <!-- Clear Search Button -->
                                @if(request('search'))
                                <a href="{{ route('jobs.index') }}" class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    Clear Search
                                </a>
                                @endif
                            </div>
                        </div>
                        @if(request('search'))
                            <div class="mt-3 text-sm text-gray-600 dark:text-gray-400">
                                Showing results for: <span class="font-medium text-gray-900 dark:text-gray-100">{{ request('search') }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">#</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Job Number</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Client</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Device</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Received</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Quotations</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($jobs as $job)
                                    @php
                                        $totalJobs = $jobs->total();
                                        $currentPage = $jobs->currentPage();
                                        $perPage = $jobs->perPage();
                                        $itemsOnPreviousPages = ($currentPage - 1) * $perPage;
                                        $itemsFromEnd = $totalJobs - $itemsOnPreviousPages - $loop->iteration + 1;
                                    @endphp
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $itemsFromEnd }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $job->job_number }}</div>
                                            @php
                                                $currentDate = now();
                                                $warrantyExpired = $job->warranty_expiry_date && $job->warranty_expiry_date->lt($currentDate);
                                                $isInWarranty = $job->warranty_status === 'In Warranty' && !$warrantyExpired;
                                            @endphp
                                            @if($isInWarranty)
                                                <div class="text-xs text-green-600 dark:text-green-400 font-medium">In Warranty</div>
                                            @else
                                                <div class="text-xs text-red-600 dark:text-red-400 font-medium">Out of Warranty</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $job->client ? $job->client->first_name . ' ' . $job->client->last_name : 'No Client' }}
                                            </div>
                                            @if($job->client && $job->client->phone)
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $job->client->phone }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $job->device_type }} - {{ $job->brand }} {{ $job->model }}
                                            </div>
                                            @if($job->fault_description)
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($job->fault_description, 40) }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($job->status == 'Received') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                @elseif($job->status == 'Diagnosing') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                @elseif($job->status == 'Waiting for parts') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                                                @elseif($job->status == 'Repairing') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                                                @elseif($job->status == 'Ready for pickup') bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200
                                                @elseif($job->status == 'Delivered') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                                @if($job->status == 'Waiting for parts')
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                    </svg>
                                                @elseif($job->status == 'Delivered')
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                    </svg>
                                                @endif
                                                {{ $job->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            <div class="font-medium">{{ $job->received_at->format('M d, Y') }}</div>
                                            <div class="text-gray-400 dark:text-gray-500">{{ $job->received_at->format('H:i') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('jobs.show', $job) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition duration-150 ease-in-out">
                                                View Details
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if($job->status === 'Diagnosing')
                                                <a href="{{ route('quotations.create.with.job', $job->job_number) }}"
                                                   class="inline-flex items-center px-3 py-2 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                    </svg>
                                                    @if(auth()->user()->role === 'technician')
                                                        Request Quotation
                                                    @else
                                                        Create Quotation
                                                    @endif
                                                </a>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-12 text-center">
                                            <div class="text-gray-500 dark:text-gray-400">
                                                @if(request('search'))
                                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                    </svg>
                                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No jobs found</h3>
                                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">No jobs match your search for "{{ request('search') }}". Try different keywords.</p>
                                                    <div class="mt-6">
                                                        <a href="{{ route('jobs.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                            Clear Search
                                                        </a>
                                                    </div>
                                                @else
                                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    </svg>
                                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No repair jobs</h3>
                                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating your first repair job.</p>
                                                    <div class="mt-6">
                                                        <a href="{{ route('jobs.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                            Create Job
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Jobs Pagination -->
                    @if($jobs->hasPages())
                        <div class="mt-6">
                            {{ $jobs->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>