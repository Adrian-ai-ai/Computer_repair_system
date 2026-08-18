<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Create Repair Job') }}
            </h2>
            <x-back-button href="{{ route('jobs.index') }}" />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('jobs.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Client Information -->
                            <div class="col-span-2">
                                <h3 class="text-lg font-medium mb-4">Client Information</h3>
                            </div>

                            <div>
                                <x-input-label for="first_name" :value="__('First Name')" />
                                <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" required autofocus autocomplete="first_name" />
                                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="last_name" :value="__('Last Name')" />
                                <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')" required autocomplete="last_name" />
                                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="phone" :value="__('Phone')" />
                                <x-text-input id="phone" class="block mt-1 w-full" type="tel" name="phone" :value="old('phone')" required autocomplete="phone" />
                                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" autocomplete="email" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <!-- Device Information -->
                            <div class="col-span-2 mt-6">
                                <h3 class="text-lg font-medium mb-4">Device Information</h3>
                            </div>

                            <div>
                                <x-input-label for="device_type" :value="__('Device Type')" />
                                <select id="device_type" name="device_type" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                    <option value="">Select Device Type</option>
                                    <option value="Computer" {{ old('device_type') == 'Computer' ? 'selected' : '' }}>Computer</option>
                                    <option value="Printer" {{ old('device_type') == 'Printer' ? 'selected' : '' }}>Printer</option>
                                </select>
                                <x-input-error :messages="$errors->get('device_type')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="brand" :value="__('Brand')" />
                                <x-text-input id="brand" class="block mt-1 w-full" type="text" name="brand" :value="old('brand')" autocomplete="brand" />
                                <x-input-error :messages="$errors->get('brand')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="model" :value="__('Model')" />
                                <x-text-input id="model" class="block mt-1 w-full" type="text" name="model" :value="old('model')" autocomplete="model" />
                                <x-input-error :messages="$errors->get('model')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="serial_number" :value="__('Serial Number')" />
                                <x-text-input id="serial_number" class="block mt-1 w-full" type="text" name="serial_number" :value="old('serial_number')" autocomplete="serial_number" />
                                <x-input-error :messages="$errors->get('serial_number')" class="mt-2" />
                            </div>

                            <!-- Repair Information -->
                            <div class="col-span-2 mt-6">
                                <h3 class="text-lg font-medium mb-4">Repair Information</h3>
                            </div>

                            <div class="col-span-2">
                                <x-input-label for="fault_description" :value="__('Fault Description')" />
                                <textarea id="fault_description" name="fault_description" rows="4" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>{{ old('fault_description') }}</textarea>
                                <x-input-error :messages="$errors->get('fault_description')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="warranty_status" :value="__('Warranty Status')" />
                                <select id="warranty_status" name="warranty_status" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                    <option value="">Select Warranty Status</option>
                                    <option value="In Warranty" {{ old('warranty_status') == 'In Warranty' ? 'selected' : '' }}>In Warranty</option>
                                    <option value="Out of Warranty" {{ old('warranty_status') == 'Out of Warranty' ? 'selected' : '' }}>Out of Warranty</option>
                                    <option value="Unknown" {{ old('warranty_status') == 'Unknown' ? 'selected' : '' }}>Unknown</option>
                                </select>
                                <x-input-error :messages="$errors->get('warranty_status')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="warranty_expiry_date" :value="__('Warranty Expiry Date')" />
                                <x-text-input id="warranty_expiry_date" class="block mt-1 w-full" type="date" name="warranty_expiry_date" :value="old('warranty_expiry_date')" />
                                <x-input-error :messages="$errors->get('warranty_expiry_date')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Accessories Information -->
                        <div class="col-span-2 mt-6">
                            <h3 class="text-lg font-medium mb-4">Accessories Included</h3>
                            <div id="accessories-container">
                                <div class="accessory-item grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 p-4 border border-gray-200 dark:border-gray-600 rounded-lg">
                                    <div>
                                        <x-input-label for="accessories[0][name]" :value="__('Accessory Name')" />
                                        <x-text-input id="accessories[0][name]" class="block mt-1 w-full" type="text" name="accessories[0][name]" :value="old('accessories.0.name')" placeholder="e.g., Power Cable" />
                                        <x-input-error :messages="$errors->get('accessories.0.name')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="accessories[0][description]" :value="__('Description')" />
                                        <x-text-input id="accessories[0][description]" class="block mt-1 w-full" type="text" name="accessories[0][description]" :value="old('accessories.0.description')" placeholder="Optional description" />
                                        <x-input-error :messages="$errors->get('accessories.0.description')" class="mt-2" />
                                    </div>
                                    <div class="flex items-end space-x-2">
                                        <div class="flex-1">
                                            <x-input-label for="accessories[0][quantity]" :value="__('Quantity')" />
                                            <x-text-input id="accessories[0][quantity]" class="block mt-1 w-full" type="number" name="accessories[0][quantity]" :value="old('accessories.0.quantity', 1)" min="1" />
                                            <x-input-error :messages="$errors->get('accessories.0.quantity')" class="mt-2" />
                                        </div>
                                        <button type="button" onclick="removeAccessory(this)" class="px-3 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 text-sm">
                                            Remove
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" onclick="addAccessory()" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 text-sm">
                                + Add Another Accessory
                            </button>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button>
                                {{ __('Create Repair Job') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let accessoryIndex = 1;

        function addAccessory() {
            const container = document.getElementById('accessories-container');
            const newAccessory = document.createElement('div');
            newAccessory.className = 'accessory-item grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 p-4 border border-gray-200 dark:border-gray-600 rounded-lg';
            newAccessory.innerHTML = `
                <div>
                    <label for="accessories[${accessoryIndex}][name]" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Accessory Name</label>
                    <input id="accessories[${accessoryIndex}][name]" class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" type="text" name="accessories[${accessoryIndex}][name]" placeholder="e.g., Power Cable">
                </div>
                <div>
                    <label for="accessories[${accessoryIndex}][description]" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                    <input id="accessories[${accessoryIndex}][description]" class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" type="text" name="accessories[${accessoryIndex}][description]" placeholder="Optional description">
                </div>
                <div class="flex items-end space-x-2">
                    <div class="flex-1">
                        <label for="accessories[${accessoryIndex}][quantity]" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Quantity</label>
                        <input id="accessories[${accessoryIndex}][quantity]" class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" type="number" name="accessories[${accessoryIndex}][quantity]" value="1" min="1">
                    </div>
                    <button type="button" onclick="removeAccessory(this)" class="px-3 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 text-sm">
                        Remove
                    </button>
                </div>
            `;
            container.appendChild(newAccessory);
            accessoryIndex++;
        }

        function removeAccessory(button) {
            button.closest('.accessory-item').remove();
        }
    </script>

    <script>
        // Auto-update warranty status based on expiry date
        document.addEventListener('DOMContentLoaded', function() {
            const warrantyExpiryDate = document.getElementById('warranty_expiry_date');
            const warrantyStatus = document.getElementById('warranty_status');

            function updateWarrantyStatus() {
                if (warrantyExpiryDate.value) {
                    const expiryDate = new Date(warrantyExpiryDate.value);
                    const currentDate = new Date();
                    
                    // Set current date to end of day for accurate comparison
                    currentDate.setHours(23, 59, 59, 999);
                    
                    if (expiryDate < currentDate) {
                        warrantyStatus.value = 'Out of Warranty';
                    } else if (expiryDate >= currentDate) {
                        warrantyStatus.value = 'In Warranty';
                    }
                }
            }

            // Update status when expiry date changes
            warrantyExpiryDate.addEventListener('change', updateWarrantyStatus);
            
            // Also update on blur (when user clicks away)
            warrantyExpiryDate.addEventListener('blur', updateWarrantyStatus);
        });
    </script>
</x-app-layout>