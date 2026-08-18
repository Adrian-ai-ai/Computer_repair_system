<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Add Part to Job') }} {{ $job->job_number }}
            </h2>
            <x-back-button href="{{ route('jobs.show', $job) }}" />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('jobs.store-part', $job) }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="product_id" :value="__('Select Product')" />
                                <select id="product_id" name="product_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                    <option value="">Select Product</option>
                                    @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }} (Stock: {{ $product->current_stock ?? 0 }})</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('product_id')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="quantity_used" :value="__('Quantity Used')" />
                                <x-text-input id="quantity_used" class="block mt-1 w-full" type="number" name="quantity_used" :value="old('quantity_used')" min="1" required />
                                <x-input-error :messages="$errors->get('quantity_used')" class="mt-2" />
                            </div>

                            <div class="col-span-2">
                                <label class="flex items-center">
                                    <input type="checkbox" name="is_warranty" value="1" {{ old('is_warranty') ? 'checked' : '' }} class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" />
                                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('This is a warranty replacement') }}</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('jobs.show', $job) }}" class="mr-4 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">Cancel</a>
                            <x-primary-button>
                                {{ __('Add Part') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>