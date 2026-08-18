<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Create Quotation
            </h2>
            <x-back-button href="{{ route('jobs.index') }}" />
        </div>
    </x-slot>

    <!-- Success/Error Messages -->
    @if(isset($existingQuotation) && $existingQuotation)
        <div class="mb-6 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 text-yellow-800 dark:text-yellow-200 px-4 py-3 rounded-lg relative">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2 text-yellow-600 dark:text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <span class="font-medium">Quotation Request Already Sent</span>
                    <p class="mt-1 text-sm">A quotation request for this job has already been sent to administrators. Please wait for approval before proceeding.</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('success'))
        <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 px-4 py-3 rounded-lg relative">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="absolute top-2 right-2 text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-200">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 px-4 py-3 rounded-lg relative">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="absolute top-2 right-2 text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-200">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 px-4 py-3 rounded-lg relative">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <span class="font-medium">Please fix the following errors:</span>
                    <ul class="mt-1 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="absolute top-2 right-2 text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-200">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Create Quotation @if(isset($jobData))for Job: {{ $jobData->job_number }}@endif</h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Fill in the details below to create a new quotation</p>
                    </div>
                    <form action="{{ route('quotations.store') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <!-- Job and Client Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if(isset($jobData))
                                <div>
                                    <label for="job_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Job Number</label>
                                    <input type="text" id="job_number" name="job_number" value="{{ $jobData->job_number }}" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm focus:ring-amber-500 focus:border-amber-500" readonly>
                                </div>
                                <div>
                                    <label for="client_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Client</label>
                                    <input type="text" id="client_name" name="client_name" value="{{ $jobData->client_name ?? 'N/A' }}" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm focus:ring-amber-500 focus:border-amber-500" readonly>
                                    <input type="hidden" name="client_id" value="{{ $jobData->client_id ?? '' }}">
                                    <input type="hidden" id="client_email" name="client_email" value="{{ $jobData->client_email ?? '' }}">
                                </div>
                            @else
                                <div>
                                    <label for="jobSelect" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Select Job (Optional)</label>
                                    <select id="jobSelect" name="job_number" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm focus:ring-amber-500 focus:border-amber-500">
                                        <option value="">-- Select Job --</option>
                                        <option value="">-- Select Job --</option>
                                        @php
                                            $jobs = DB::table('jobs')->where('status', 'Diagnosing')->orderBy('created_at', 'desc')->get();
                                        @endphp
                                        @foreach($jobs as $job)
                                            <option value="{{ $job->job_number }}">{{ $job->job_number }} - {{ $job->device_type ?? 'N/A' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="clientSelect" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Client</label>
                                    <select id="clientSelect" name="client_id" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm focus:ring-amber-500 focus:border-amber-500">
                                        <option value="">-- Select Client --</option>
                                        @php
                                            $clients = DB::table('clients')->orderBy('name')->get();
                                        @endphp
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        </div>

                        <!-- Client Email and Additional Info -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="client_email_display" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Client Email</label>
                                <input type="email" id="client_email_display" name="client_email_display" 
                                       value="{{ $jobData->client_email ?? '' }}" 
                                       class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm focus:ring-amber-500 focus:border-amber-500" readonly>
                            </div>
                            @if(isset($jobData))
                                <div>
                                    <label for="client_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Client Phone</label>
                                    <input type="tel" id="client_phone" name="client_phone" 
                                           value="{{ $jobData->client_phone ?? '' }}" 
                                           class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm bg-gray-50 dark:bg-gray-600" readonly>
                                </div>
                            @endif
                        </div>

                        @if(isset($jobData))
                        <!-- Device Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="device_serial" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Device Serial Number</label>
                                <input type="text" id="device_serial" name="device_serial" 
                                       value="{{ $jobData->serial_number ?? '' }}" 
                                       class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm bg-gray-50 dark:bg-gray-600" readonly>
                            </div>
                            <div>
                                <label for="device_info" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Device Information</label>
                                <input type="text" id="device_info" name="device_info" 
                                       value="{{ $jobData->device_type ?? '' }} - {{ $jobData->brand ?? '' }} {{ $jobData->model ?? '' }}" 
                                       class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm bg-gray-50 dark:bg-gray-600" readonly>
                            </div>
                        </div>
                        
                        <!-- Fault Description -->
                        <div>
                            <label for="fault_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fault Description</label>
                            <textarea id="fault_description" name="fault_description" rows="3" 
                                      class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm bg-gray-50 dark:bg-gray-600" readonly>{{ $jobData->fault_description ?? '' }}</textarea>
                        </div>
                        @endif

                        <!-- Valid Until Date - Required for all users -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="valid_until" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Valid Until <span class="text-red-500">*</span></label>
                                <input type="date" id="valid_until" name="valid_until" required class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm focus:ring-amber-500 focus:border-amber-500">
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">This quotation is valid until this date.</p>
                            </div>
                        </div>

                        @if(auth()->user()->role !== 'technician')
                        <!-- Additional Information for Admins -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <!-- Admin-only fields can go here -->
                            </div>
                        </div>
                        @endif

                        <!-- Quotation Items -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Quotation Items</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700" id="itemsTable">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Description</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Quantity</th>
                                            @if(auth()->user()->role !== 'technician')
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Unit Price</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total</th>
                                            @endif
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4">
                                <button type="button" onclick="addRow()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Add Item
                                </button>
                            </div>
                        </div>

                        @if(auth()->user()->role !== 'technician')
                        <!-- Financial Summary (Hidden for Technicians) -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Financial Summary</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                <div>
                                    <label for="subtotal" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Subtotal</label>
                                    <input type="number" step="0.01" id="subtotal" name="subtotal" readonly class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm bg-gray-50 dark:bg-gray-600">
                                </div>
                                <div>
                                    <label for="tax_rate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tax Rate (%)</label>
                                    <input type="number" step="0.01" id="tax_rate" name="tax_rate" value="16" min="0" max="100" readonly class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm bg-gray-50 dark:bg-gray-600">
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Tax rate is fixed at 16%</p>
                                </div>
                                <div>
                                    <label for="tax" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tax Amount</label>
                                    <input type="number" step="0.01" id="tax" name="tax" value="0" readonly class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm bg-gray-50 dark:bg-gray-600">
                                </div>
                                <div>
                                    <label for="discount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Discount</label>
                                    <input type="number" step="0.01" id="discount" name="discount" value="0" onchange="calculateTotal()" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm focus:ring-amber-500 focus:border-amber-500">
                                </div>
                                <div>
                                    <label for="total_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Total Amount</label>
                                    <input type="number" step="0.01" id="total_amount" name="total_amount" readonly class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm bg-gray-50 dark:bg-gray-600 font-semibold text-amber-600">
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Submit Actions -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition duration-150 ease-in-out">
                                Cancel
                            </a>
                            @if(isset($existingQuotation) && $existingQuotation)
                                <button type="button" disabled class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-gray-400 bg-gray-300 dark:bg-gray-600 cursor-not-allowed">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                    Quotation Request Already Sent
                                </button>
                            @else
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                    </svg>
                                    @if(auth()->user()->role === 'technician')
                                        Send Request to Admin
                                    @else
                                        Send Quotation
                                    @endif
                                </button>
                            @endif
                        </div>
                        
                        @if(auth()->user()->role === 'technician')
                        <!-- Hidden financial fields for technicians (required for form submission) -->
                        <input type="hidden" name="subtotal" id="subtotal_hidden" value="0">
                        <input type="hidden" name="tax" id="tax_hidden" value="0">
                        <input type="hidden" name="total_amount" id="total_amount_hidden" value="0">
                        <input type="hidden" name="discount" id="discount_hidden" value="0">
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
let rowCount = 0;

// Add initial row when page loads
document.addEventListener('DOMContentLoaded', function() {
    addRow();
    
    // Set default valid_until date to 30 days from now for all users
    const validUntil = new Date();
    validUntil.setDate(validUntil.getDate() + 30);
    const validUntilField = document.getElementById('valid_until');
    if (validUntilField) {
        validUntilField.value = validUntil.toISOString().split('T')[0];
    }
    
    // Add event listeners for tax rate and discount fields
    const taxRateField = document.getElementById('tax_rate');
    const discountField = document.getElementById('discount');
    
    if (taxRateField) {
        taxRateField.addEventListener('input', calculateTaxAndTotal);
    }
    if (discountField) {
        discountField.addEventListener('input', calculateTotal);
    }
});

function addRow() {
    const tbody = document.querySelector('#itemsTable tbody');
    const row = document.createElement('tr');
    row.setAttribute('data-row-id', rowCount);
    
    const isTechnician = {{ auth()->user()->role === 'technician' ? 'true' : 'false' }};
    
    let priceColumns = '';
    if (!isTechnician) {
        priceColumns = `
        <td class="px-6 py-4 whitespace-nowrap">
            <input type="number" name="items[${rowCount}][unit_price]" 
                   class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm focus:ring-amber-500 focus:border-amber-500 price" 
                   min="0" step="0.01" placeholder="0.00" required onchange="calculateItemTotal(${rowCount}); calculateSubtotal()">
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <input type="number" name="items[${rowCount}][total]" 
                   class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm bg-gray-50 dark:bg-gray-600 total" 
                   readonly value="0.00">
        </td>`;
    } else {
        // Add hidden unit_price field for technicians
        priceColumns = `
        <input type="hidden" name="items[${rowCount}][unit_price]" value="0.00">`;
    }
    
    row.innerHTML = `
        <td class="px-6 py-4 whitespace-nowrap">
            <input type="text" name="items[${rowCount}][description]" 
                   class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm focus:ring-amber-500 focus:border-amber-500" 
                   placeholder="Item description" required onchange="calculateSubtotal()">
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <input type="number" name="items[${rowCount}][quantity]" 
                   class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm focus:ring-amber-500 focus:border-amber-500 quantity" 
                   min="1" value="1" required onchange="calculateItemTotal(${rowCount}); calculateSubtotal()">
        </td>
        ${priceColumns}
        <td class="px-6 py-4 whitespace-nowrap">
            <button type="button" onclick="removeRow(${rowCount})" 
                    class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 ease-in-out">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                Remove
            </button>
        </td>
    `;
    
    tbody.appendChild(row);
    rowCount++;
}

function calculateItemTotal(rowId) {
    const isTechnician = {{ auth()->user()->role === 'technician' ? 'true' : 'false' }};
    
    if (!isTechnician) {
        const quantity = parseFloat(document.querySelector(`tr[data-row-id="${rowId}"] .quantity`).value) || 0;
        const unitPrice = parseFloat(document.querySelector(`tr[data-row-id="${rowId}"] .price`).value) || 0;
        const total = quantity * unitPrice;
        document.querySelector(`tr[data-row-id="${rowId}"] .total`).value = total.toFixed(2);
    }
}

function removeRow(rowId) {
    const row = document.querySelector(`tr[data-row-id="${rowId}"]`);
    if (row) {
        row.remove();
        calculateSubtotal();
    }
}

function calculateSubtotal() {
    const isTechnician = {{ auth()->user()->role === 'technician' ? 'true' : 'false' }};
    
    if (!isTechnician) {
        let subtotal = 0;
        const totalInputs = document.querySelectorAll('.total');
        
        totalInputs.forEach(input => {
            subtotal += parseFloat(input.value) || 0;
        });
        
        document.getElementById('subtotal').value = subtotal.toFixed(2);
        calculateTaxAndTotal();
    } else {
        // For technicians, set all financial values to 0
        document.getElementById('subtotal_hidden').value = '0.00';
        document.getElementById('tax_hidden').value = '0.00';
        document.getElementById('total_amount_hidden').value = '0.00';
        document.getElementById('discount_hidden').value = '0.00';
    }
}

function calculateTaxAndTotal() {
    const subtotal = parseFloat(document.getElementById('subtotal').value) || 0;
    const taxRate = parseFloat(document.getElementById('tax_rate').value) || 0;
    const discount = parseFloat(document.getElementById('discount').value) || 0;
    
    // Calculate tax amount as percentage of subtotal
    const taxAmount = (subtotal * taxRate) / 100;
    document.getElementById('tax').value = taxAmount.toFixed(2);
    
    // Calculate total: Subtotal + Tax Amount - Discount
    const total = subtotal + taxAmount - discount;
    document.getElementById('total_amount').value = total.toFixed(2);
}

function calculateTotal() {
    const subtotal = parseFloat(document.getElementById('subtotal').value) || 0;
    const taxAmount = parseFloat(document.getElementById('tax').value) || 0;
    const discount = parseFloat(document.getElementById('discount').value) || 0;
    
    // Calculate total: Subtotal + Tax Amount - Discount
    const total = subtotal + taxAmount - discount;
    document.getElementById('total_amount').value = total.toFixed(2);
}

// Initialize tax rate to 16% on page load
document.addEventListener('DOMContentLoaded', function() {
    const taxRateInput = document.getElementById('tax_rate');
    if (taxRateInput) {
        taxRateInput.value = '16';
    }
});
</script>
