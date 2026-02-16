<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Job :number', ['number' => $job->job_number]) }}
            </h2>
            <x-back-button href="{{ route('jobs.index') }}" />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Job Details Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Job #{{ $job->job_number }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Complete details and management for repair job {{ $job->job_number }}</p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div class="space-y-6">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-700 pb-2">Job Information</h4>
                            <div class="space-y-6">
                                <div class="flex items-start space-x-4 py-3">
                                    <div class="flex-shrink-0 w-8 h-8 bg-indigo-100 dark:bg-indigo-900/30 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 pt-1">
                                        <span class="font-medium text-gray-700 dark:text-gray-300 block mb-2">Client:</span>
                                        <span class="text-gray-900 dark:text-gray-100 text-lg">{{ $job->client ? $job->client->first_name . ' ' . $job->client->last_name : 'No Client' }}</span>
                                    </div>
                                </div>
                                
                                @if($job->client && $job->client->email)
                                <div class="flex items-start space-x-4 py-3">
                                    <div class="flex-shrink-0 w-8 h-8 bg-indigo-100 dark:bg-indigo-900/30 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 pt-1">
                                        <span class="font-medium text-gray-700 dark:text-gray-300 block mb-2">Email:</span>
                                        <span class="text-gray-900 dark:text-gray-100 text-lg">{{ $job->client->email }}</span>
                                    </div>
                                </div>
                                @endif
                                
                                @if($job->client && $job->client->phone)
                                <div class="flex items-start space-x-4 py-3">
                                    <div class="flex-shrink-0 w-8 h-8 bg-indigo-100 dark:bg-indigo-900/30 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 pt-1">
                                        <span class="font-medium text-gray-700 dark:text-gray-300 block mb-2">Phone:</span>
                                        <span class="text-gray-900 dark:text-gray-100 text-lg">{{ $job->client->phone }}</span>
                                    </div>
                                </div>
                                @endif
                                
                                <div class="flex items-start space-x-4 py-3">
                                    <div class="flex-shrink-0 w-8 h-8 bg-indigo-100 dark:bg-indigo-900/30 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm12 12H4l2-4 2 4 2-4 2 4 2-4 2 4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 pt-1">
                                        <span class="font-medium text-gray-700 dark:text-gray-300 block mb-2">Device:</span>
                                        <span class="text-gray-900 dark:text-gray-100 text-lg">{{ $job->device_type }} - {{ $job->brand }} {{ $job->model }}</span>
                                    </div>
                                </div>
                                
                                <div class="flex items-start space-x-4 py-3">
                                    <div class="flex-shrink-0 w-8 h-8 bg-indigo-100 dark:bg-indigo-900/30 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 pt-1">
                                        <span class="font-medium text-gray-700 dark:text-gray-300 block mb-2">Serial Number:</span>
                                        <span class="text-gray-900 dark:text-gray-100 text-lg">{{ $job->serial_number ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                
                                <div class="flex items-start space-x-4 py-3">
                                    <div class="flex-shrink-0 w-8 h-8 bg-indigo-100 dark:bg-indigo-900/30 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 pt-1">
                                        <span class="font-medium text-gray-700 dark:text-gray-300 block mb-2">Status:</span>
                                        <div class="mt-2">
                                            <span class="px-4 py-2 text-sm font-semibold rounded-full
                                                @if($job->status == 'Received') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300
                                                @elseif($job->status == 'Diagnosing') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                                                @elseif($job->status == 'Waiting for parts') bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300
                                                @elseif($job->status == 'Repairing') bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300
                                                @elseif($job->status == 'Ready for pickup') bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300
                                                @elseif($job->status == 'Delivered') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                                                @else bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 @endif">
                                                {{ $job->status }}
                                            </span>
                                        </div>
                                        @if($job->status === 'Diagnosing')
                                            <div class="mt-4">
                                                <a href="{{ route('quotations.create.with.job', $job->job_number) }}" 
                                                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                    </svg>
                                                    Create Quotation
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-700 pb-2">Warranty Information</h4>
                            <div class="space-y-6">
                                <div class="flex items-start space-x-4 py-3">
                                    <div class="flex-shrink-0 w-8 h-8 bg-indigo-100 dark:bg-indigo-900/30 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 pt-1">
                                        <span class="font-medium text-gray-700 dark:text-gray-300 block mb-2">Warranty Status:</span>
                                        <span class="text-gray-900 dark:text-gray-100 text-lg">{{ $job->warranty_status }}</span>
                                    </div>
                                </div>
                                
                                @if($job->warranty_expiry_date)
                                <div class="flex items-start space-x-4 py-3">
                                    <div class="flex-shrink-0 w-8 h-8 bg-indigo-100 dark:bg-indigo-900/30 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 pt-1">
                                        <span class="font-medium text-gray-700 dark:text-gray-300 block mb-2">Warranty Expires:</span>
                                        <span class="text-gray-900 dark:text-gray-100 text-lg">{{ $job->warranty_expiry_date->format('M d, Y') }}</span>
                                    </div>
                                </div>
                                @endif
                                
                                <div class="flex items-start space-x-4 py-3">
                                    <div class="flex-shrink-0 w-8 h-8 bg-indigo-100 dark:bg-indigo-900/30 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 pt-1">
                                        <span class="font-medium text-gray-700 dark:text-gray-300 block mb-2">Warranty Check:</span>
                                        <div class="mt-2">
                                            @php
                                                $currentDate = now();
                                                $warrantyExpired = $job->warranty_expiry_date && $job->warranty_expiry_date->lt($currentDate);
                                                $isInWarranty = $job->warranty_status === 'In Warranty' && !$warrantyExpired;
                                            @endphp
                                            @if($isInWarranty)
                                                <span class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Under Warranty
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Out of Warranty
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($job->fault_description)
                    <!-- Fault Description Section -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Fault Description</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Customer reported issue and problem details</p>
                            </div>
                            <div class="bg-amber-50 dark:bg-amber-900/20 border-l-4 border-amber-400 p-4 rounded">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-gray-900 dark:text-gray-100 leading-relaxed">{{ $job->fault_description }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Accessories Section -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-6">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Accessories Included</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Items that came with the device</p>
                                </div>
                            </div>
                            @if($job->accessories->count() > 0)
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg overflow-hidden shadow-sm">
                                    <ul class="divide-y divide-gray-200 dark:divide-gray-600">
                                        @foreach($job->accessories as $accessory)
                                            <li class="px-6 py-4 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-150">
                                                <div class="flex justify-between items-center">
                                                <div class="flex-1">
                                                    <div class="flex items-center space-x-3">
                                                        <div class="flex-shrink-0 w-8 h-8 bg-indigo-100 dark:bg-indigo-900/30 rounded-full flex items-center justify-center">
                                                            <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
                                                            </svg>
                                                        </div>
                                                        <div>
                                                            <span class="font-medium text-gray-900 dark:text-gray-100">{{ $accessory->name }}</span>
                                                            @if($accessory->description)
                                                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $accessory->description }}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex items-center space-x-3">
                                                    <span class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-200">
                                                        Qty: {{ $accessory->quantity }}
                                                    </span>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="inline-flex items-center justify-center w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-full">
                                    <svg class="w-6 h-6 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5 5a3 3 0 015-2.236A3 3 0 0114.83 6H16a2 2 0 110 4h-5V9a1 1 0 10-2 0v1H4a2 2 0 110-4h1.17C5.06 5.687 5 5.35 5 5zm4 1V5a1 1 0 10-1 1h1zm3 0a1 1 0 10-1-1v1h1z" clip-rule="evenodd"/>
                                        <path d="M9 11H3v5a2 2 0 002 2h4v-7zM11 18h4a2 2 0 002-2v-5h-6v7z"/>
                                    </svg>
                                </div>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No accessories included.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Parts Used -->
                    <div class="mb-16">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-700 pb-3">Parts Used</h3>
                            <a href="{{ route('jobs.add-part', $job) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-150 shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
                                </svg>
                                Add Part
                            </a>
                        </div>

                        @if($job->partsUsed->count() > 0)
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg overflow-hidden shadow-sm">
                                <ul class="divide-y divide-gray-200 dark:divide-gray-600">
                                    @foreach($job->partsUsed as $part)
                                        <li class="px-6 py-4 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-150">
                                            <div class="flex justify-between items-center">
                                                <div class="flex items-center space-x-3">
                                                    <div class="flex-shrink-0 w-8 h-8 bg-indigo-100 dark:bg-indigo-900/30 rounded-full flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                        </svg>
                                                    </div>
                                                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ $part->name }}</span>
                                                </div>
                                                <div class="flex items-center space-x-3">
                                                    <span class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-200">
                                                        Qty: {{ $part->pivot->quantity_used }}
                                                    </span>
                                                    @if($part->pivot->is_warranty)
                                                        <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                            </svg>
                                                            Warranty
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="inline-flex items-center justify-center w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-full">
                                    <svg class="w-6 h-6 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5 5a3 3 0 015-2.236A3 3 0 0114.83 6H16a2 2 0 110 4h-5V9a1 1 0 10-2 0v1H4a2 2 0 110-4h1.17C5.06 5.687 5 5.35 5 5zm4 1V5a1 1 0 10-1 1h1zm3 0a1 1 0 10-1-1v1h1z" clip-rule="evenodd"/>
                                        <path d="M9 11H3v5a2 2 0 002 2h4v-7zM11 18h4a2 2 0 002-2v-5h-6v7z"/>
                                    </svg>
                                </div>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No parts used yet.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Update Status -->
                    <div class="mb-16">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-700 pb-3 mb-6">Update Status</h3>
                        <form method="POST" action="{{ route('jobs.update-status', $job) }}" class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg shadow-sm">
                            @csrf
                            <div class="flex items-center space-x-6">
                                <div class="flex-1 max-w-xs">
                                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">New Status</label>
                                    <select name="status" id="status" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                        @foreach(\App\Models\Job::STATUS_FLOW as $status)
                                            @if($job->canMoveTo($status))
                                                <option value="{{ $status }}">{{ $status }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="pt-6">
                                    <x-secondary-button type="submit" class="px-6 py-2">
                                        {{ __('Update Status') }}
                                    </x-secondary-button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Status History -->
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-700 pb-3 mb-6">Status History</h3>
                        @if($job->statusHistory->count() > 0)
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg overflow-hidden shadow-sm">
                                <ul class="divide-y divide-gray-200 dark:divide-gray-600">
                                    @foreach($job->statusHistory as $history)
                                        <li class="px-6 py-4">
                                            <div class="flex justify-between items-center">
                                                <div class="flex items-center space-x-3">
                                                    <div class="flex-shrink-0 w-8 h-8 bg-indigo-100 dark:bg-indigo-900/30 rounded-full flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                        </svg>
                                                    </div>
                                                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ $history->status }}</span>
                                                </div>
                                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                                    <span class="inline-flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                        </svg>
                                                        {{ $history->changed_at->format('M d, Y H:i') }}
                                                    </span>
                                                    <span class="mx-2">•</span>
                                                    <span class="inline-flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                                        </svg>
                                                        {{ $history->user ? $history->user->name : 'Unknown User' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="inline-flex items-center justify-center w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-full">
                                    <svg class="w-6 h-6 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No status history available.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
