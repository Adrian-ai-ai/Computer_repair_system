<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Quotation Details
            </h2>
            <x-back-button href="{{ route('quotations.index') }}" />
        </div>
    </x-slot>

    <!-- CSRF Meta Tag for AJAX requests -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Header with Status and Actions -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Quotation #{{ $quotation->id }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                Created on 
                                @php
                                    $headerCreatedAt = is_string($quotation->created_at) ? \Carbon\Carbon::parse($quotation->created_at) : $quotation->created_at;
                                @endphp
                                {{ $headerCreatedAt->format('M d, Y') }}
                            </p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                @if($quotation->status == 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                @elseif($quotation->status == 'sent') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                @elseif($quotation->status == 'accepted') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                @elseif($quotation->status == 'rejected') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                @endif">
                                {{ ucfirst($quotation->status) }}
                            </span>
                            @if(auth()->user()->role !== 'technician')
                            @if($quotation->status === 'pending')
                            <button onclick="saveQuotationPrices()" class="inline-flex items-center px-4 py-2 border border-green-600 text-sm font-medium rounded-md text-green-600 bg-white hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Save Prices
                            </button>
                            @else
                            <div class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 rounded-md">
                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                <span class="text-sm text-gray-600 dark:text-gray-400">Quotation already sent - prices locked</span>
                            </div>
                            @endif
                            @endif
                        </div>
                    </div>
                    <!-- Quotation Information Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3">Job Information</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Job Number:</span>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ $quotation->job_number }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Client:</span>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ $quotation->client_name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Created By:</span>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ $quotation->created_by_name }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3">Quotation Details</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Date Created:</span>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">
                                        @php
                                            $createdAt = is_string($quotation->created_at) ? \Carbon\Carbon::parse($quotation->created_at) : $quotation->created_at;
                                        @endphp
                                        {{ $createdAt->format('M d, Y') }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Valid Until:</span>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">
                                        @php
                                            $validUntil = $quotation->valid_until ? (is_string($quotation->valid_until) ? \Carbon\Carbon::parse($quotation->valid_until) : $quotation->valid_until) : null;
                                        @endphp
                                        @if($validUntil)
                                            {{ $validUntil->format('M d, Y') }}
                                        @else
                                            Not set
                                        @endif
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Status:</span>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ ucfirst($quotation->status) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <div class="mb-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Quotation Items</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Description</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Quantity</th>
                                        @if(auth()->user()->role !== 'technician')
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Unit Price</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tax (16%)</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total</th>
                                        @else
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Unit Price</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($items as $index => $item)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $item->description }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-900 dark:text-gray-100">{{ $item->quantity }}</td>
                                        @if(auth()->user()->role !== 'technician')
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                            @if($quotation->status === 'sent')
                                                <!-- Read-only display for sent quotations -->
                                                <div class="flex items-center justify-end">
                                                    <span class="text-gray-500 mr-1">ZMW</span>
                                                    <span class="w-24 px-2 py-1 text-sm text-right text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-600 rounded-md">
                                                        {{ number_format($item->unit_price ?? 0, 2) }}
                                                    </span>
                                                </div>
                                            @else
                                                <!-- Editable input for pending quotations -->
                                                <div class="flex items-center justify-end">
                                                    <span class="text-gray-500 mr-1">ZMW</span>
                                                    <input type="number" 
                                                           name="items[{{ $index }}][unit_price]" 
                                                           value="{{ $item->unit_price ?? '0.00' }}" 
                                                           step="0.01" 
                                                           min="0"
                                                           class="w-24 px-2 py-1 text-sm text-right border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 price"
                                                           data-item-index="{{ $index }}"
                                                           data-quantity="{{ $item->quantity }}"
                                                           onchange="calculateItemTotals();"
                                                           oninput="calculateItemTotals();">
                                                    <!-- Hidden item ID for reliable updates -->
                                                    <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-600 dark:text-gray-400">
                                            ZMW<span class="item-tax" data-item-index="{{ $index }}">{{ number_format((($item->unit_price * $item->quantity) * 0.16) ?? 0, 2) }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-gray-900 dark:text-gray-100">
                                            ZMW<span class="item-total" data-item-index="{{ $index }}">{{ number_format($item->total ?? 0, 2) }}</span>
                                        </td>
                                        @else
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-gray-100">ZMW{{ number_format($item->unit_price ?? 0, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-gray-900 dark:text-gray-100">ZMW{{ number_format($item->total ?? 0, 2) }}</td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Financial Summary -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mb-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3">Financial Summary</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Subtotal:</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">ZMW<span id="summary-subtotal">{{ number_format($quotation->subtotal, 2) }}</span></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Tax (16%):</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">ZMW<span id="summary-tax">{{ number_format($quotation->tax, 2) }}</span></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Discount:</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">ZMW{{ number_format($quotation->discount, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-lg font-bold text-amber-600 dark:text-amber-400 pt-2 border-t border-gray-200 dark:border-gray-600">
                                <span>Total:</span>
                                <span>ZMW<span id="summary-total">{{ number_format($quotation->total_amount, 2) }}</span></span>
                            </div>
                        </div>
                    </div>

                    <!-- Important Notes -->
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 p-4 rounded-lg">
                        <h4 class="text-lg font-semibold text-yellow-800 dark:text-yellow-200 mb-3">Important Notes</h4>
                        <div class="space-y-2 text-sm text-yellow-700 dark:text-yellow-300">
                            <p>This quotation is valid until 
                                <strong>
                                    @php
                                        $notesValidUntil = $quotation->valid_until ? (is_string($quotation->valid_until) ? \Carbon\Carbon::parse($quotation->valid_until) : $quotation->valid_until) : null;
                                    @endphp
                                    @if($notesValidUntil)
                                        {{ $notesValidUntil->format('M d, Y') }}
                                    @else
                                        Not specified
                                    @endif
                                </strong>.
                            </p>
                            <p>Please review all items carefully before confirming.</p>
                            <p>Prices are locked in until the expiry date.</p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-3 mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        @if($quotation->status == 'pending' && auth()->user()->role !== 'technician')
                        <button onclick="sendQuotation({{ $quotation->id }})" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            Send to Client
                        </button>
                        @endif
                        @if($quotation->status == 'sent' && auth()->user()->role !== 'technician')
                        <button onclick="acceptQuotation({{ $quotation->id }})" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Accept Quotation
                        </button>
                        @endif
                        <button onclick="printQuotation()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-150 ease-in-out">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            Print
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
function acceptQuotation(quotationId) {
    if (confirm('Are you sure you want to accept this quotation? This will update the job status to "In Progress".')) {
        // Show loading state
        const acceptButton = document.querySelector(`button[onclick="acceptQuotation(${quotationId})"]`);
        const originalText = acceptButton.innerHTML;
        acceptButton.innerHTML = '<svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Accepting...';
        acceptButton.disabled = true;

        fetch(`/quotations/${quotationId}/accept`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Show success message
                showSuccessMessage(data.message);
                // Reload page after a short delay
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                throw new Error(data.message || 'Failed to accept quotation');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorMessage(error.message || 'Failed to accept quotation. Please try again.');
            // Restore button state
            acceptButton.innerHTML = originalText;
            acceptButton.disabled = false;
        });
    }
}

function sendQuotation(quotationId) {
    if (confirm('Are you sure you want to send this quotation to the client?')) {
        // Show loading state
        const sendButton = document.querySelector(`button[onclick="sendQuotation(${quotationId})"]`);
        const originalText = sendButton.innerHTML;
        sendButton.innerHTML = '<svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Sending...';
        sendButton.disabled = true;

        fetch(`/quotations/${quotationId}/send`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Show success message
                showSuccessMessage(data.message);
                // Reload page after a short delay
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                throw new Error(data.message || 'Failed to send quotation');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorMessage(error.message || 'Failed to send quotation. Please try again.');
            // Restore button state
            sendButton.innerHTML = originalText;
            sendButton.disabled = false;
        });
    }
}

function showSuccessMessage(message) {
    // Remove any existing messages
    removeMessages();
    
    const messageDiv = document.createElement('div');
    messageDiv.className = 'fixed top-4 right-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 px-4 py-3 rounded-lg shadow-lg z-50';
    messageDiv.innerHTML = `
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span class="font-medium">${message}</span>
        </div>
    `;
    document.body.appendChild(messageDiv);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (messageDiv.parentNode) {
            messageDiv.parentNode.removeChild(messageDiv);
        }
    }, 5000);
}

function showErrorMessage(message) {
    // Remove any existing messages
    removeMessages();
    
    const messageDiv = document.createElement('div');
    messageDiv.className = 'fixed top-4 right-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 px-4 py-3 rounded-lg shadow-lg z-50';
    messageDiv.innerHTML = `
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            <span class="font-medium">${message}</span>
        </div>
    `;
    document.body.appendChild(messageDiv);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (messageDiv.parentNode) {
            messageDiv.parentNode.removeChild(messageDiv);
        }
    }, 5000);
}

function removeMessages() {
    const messages = document.querySelectorAll('[class*="fixed top-4 right-4"]');
    messages.forEach(msg => msg.remove());
}

function printQuotation() {
    window.print();
}

function calculateItemTotals() {
    const taxRate = 0.16;
    let subtotal = 0;
    let totalTax = 0;
    let grandTotal = 0;
    
    // Get all price inputs
    const priceInputs = document.querySelectorAll('input[name*="[unit_price]"]');
    
    priceInputs.forEach((input, index) => {
        const itemIndex = input.dataset.itemIndex;
        const quantity = parseFloat(input.dataset.quantity) || 0;
        const unitPrice = parseFloat(input.value) || 0;
        
        // Calculate: quantity * unit_price = item_total
        const itemTotal = quantity * unitPrice;
        // Calculate tax: item_total * 16%
        const itemTax = itemTotal * taxRate;
        // Final total: item_total + tax
        const itemFinalTotal = itemTotal + itemTax;
        
        // Update tax display
        const taxElement = document.querySelector(`.item-tax[data-item-index="${itemIndex}"]`);
        if (taxElement) {
            taxElement.textContent = itemTax.toFixed(2);
        }
        
        // Update total display
        const totalElement = document.querySelector(`.item-total[data-item-index="${itemIndex}"]`);
        if (totalElement) {
            totalElement.textContent = itemFinalTotal.toFixed(2);
        }
        
        subtotal += itemTotal;
        totalTax += itemTax;
        grandTotal += itemFinalTotal;
    });
    
    // Update financial summary - ensure total_amount is the grand total
    const subtotalElement = document.querySelector('#summary-subtotal');
    if (subtotalElement) {
        subtotalElement.textContent = subtotal.toFixed(2);
    }
    
    const taxElement = document.querySelector('#summary-tax');
    if (taxElement) {
        taxElement.textContent = totalTax.toFixed(2);
    }
    
    const totalElement = document.querySelector('#summary-total');
    if (totalElement) {
        totalElement.textContent = grandTotal.toFixed(2);
    }
}

// Don't auto-calculate on page load - let database values show
// Only calculate when user actually changes a price

function validateQuotationForSending() {
    // Check if quotation is in pending status
    const statusBadge = document.querySelector('span[class*="bg-yellow-100"]');
    if (!statusBadge) {
        showErrorMessage('This quotation has already been processed and cannot be sent again.');
        return false;
    }
    
    // Validate prices (not null, 0, or negative)
    const validateInputs = document.querySelectorAll('input[name*="[unit_price]"]');
    let hasError = false;
    let errorMessage = '';
    
    validateInputs.forEach((input, index) => {
        const price = parseFloat(input.value) || 0;
        if (isNaN(price) || price <= 0 || input.value === '' || input.value === null) {
            hasError = true;
            errorMessage = `Item ${index + 1}: Unit price must be greater than 0 before sending quotation. Current value: "${input.value}"`;
            input.classList.add('border-red-500');
            input.focus();
            return;
        } else {
            input.classList.remove('border-red-500');
        }
    });
    
    if (hasError) {
        showErrorMessage(errorMessage);
        return false;
    }
    
    // Check if total is valid (not null, 0, or negative)
    const total = parseFloat(document.querySelector('#summary-total').textContent) || 0;
    if (isNaN(total) || total <= 0) {
        showErrorMessage('Quotation total must be greater than 0 before sending. Current total: ' + total);
        return false;
    }
    
    return true;
}

function saveQuotationPrices() {
    // Allow zero prices for saving (admins may be working incrementally)
    const saveInputs = document.querySelectorAll('input[name*="[unit_price]"]');
    let hasError = false;
    let errorMessage = '';
    
    saveInputs.forEach((input, index) => {
        const price = parseFloat(input.value) || 0;
        if (price < 0) {
            hasError = true;
            errorMessage = `Item ${index + 1}: Unit price cannot be negative`;
            input.classList.add('border-red-500');
            input.focus();
            return;
        } else {
            input.classList.remove('border-red-500');
        }
    });
    
    if (hasError) {
        showErrorMessage(errorMessage);
        return;
    }
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/quotations/{{ $quotation->id }}/update-prices`;
    
    // Add CSRF token
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    form.appendChild(csrfToken);
    
    // Add all price inputs
    const formInputs = document.querySelectorAll('input[name*="[unit_price]"]');
    formInputs.forEach(input => {
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = input.name;
        hiddenInput.value = input.value;
        form.appendChild(hiddenInput);
        
        // Add the item ID as well
        const itemIndex = input.dataset.itemIndex;
        const idInput = document.querySelector(`input[name="items[${itemIndex}][id]"]`);
        if (idInput) {
            const hiddenIdInput = document.createElement('input');
            hiddenIdInput.type = 'hidden';
            hiddenIdInput.name = `items[${itemIndex}][id]`;
            hiddenIdInput.value = idInput.value;
            form.appendChild(hiddenIdInput);
        }
    });
    
    // Add calculated totals
    const subtotal = document.querySelector('#summary-subtotal').textContent;
    const tax = document.querySelector('#summary-tax').textContent;
    const total = document.querySelector('#summary-total').textContent;
    
    const subtotalInput = document.createElement('input');
    subtotalInput.type = 'hidden';
    subtotalInput.name = 'subtotal';
    subtotalInput.value = subtotal;
    form.appendChild(subtotalInput);
    
    const taxInput = document.createElement('input');
    taxInput.type = 'hidden';
    taxInput.name = 'tax';
    taxInput.value = tax;
    form.appendChild(taxInput);
    
    const totalInput = document.createElement('input');
    totalInput.type = 'hidden';
    totalInput.name = 'total_amount';
    totalInput.value = total;
    form.appendChild(totalInput);
    
    // Submit form
    document.body.appendChild(form);
    form.submit();
}
</script>
