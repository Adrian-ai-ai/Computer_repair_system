<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Send Products Report
            </h2>
            <x-back-button href="{{ route('reports.index') }}" />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Products Email Report</h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Send detailed product catalog and usage reports to staff members</p>
                    </div>

                    <form method="POST" action="{{ route('reports.products.send') }}">
                        @csrf

                        <!-- Recipients -->
                        <div class="mb-6">
                            <x-input-label for="recipients" :value="__('Recipients')" />
                            <div class="mt-2 space-y-2">
                                @foreach($staff as $user)
                                <label class="flex items-center">
                                    <input type="checkbox" name="recipients[]" value="{{ $user->id }}" 
                                           class="rounded border-gray-300 dark:border-gray-600 text-orange-600 shadow-sm focus:ring-orange-500 dark:focus:ring-orange-600 dark:focus:ring-offset-gray-800">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                        {{ $user->name }} ({{ $user->role }})
                                    </span>
                                </label>
                                @endforeach
                            </div>
                            <x-input-error :messages="$errors->get('recipients')" class="mt-2" />
                        </div>

                        <!-- Subject -->
                        <div class="mb-6">
                            <x-input-label for="subject" :value="__('Subject')" />
                            <x-text-input id="subject" class="block mt-1 w-full" type="text" name="subject" 
                                         value="Products Report - {{ now()->format('M d, Y') }}" required />
                            <x-input-error :messages="$errors->get('subject')" class="mt-2" />
                        </div>

                        <!-- Message -->
                        <div class="mb-6">
                            <x-input-label for="message" :value="__('Message (Optional)')" />
                            <textarea id="message" name="message" rows="4" 
                                      class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 dark:focus:ring-orange-600 dark:focus:ring-offset-gray-800"
                                      placeholder="Add a custom message to include with the products report..."></textarea>
                            <x-input-error :messages="$errors->get('message')" class="mt-2" />
                        </div>

                        <!-- Report Preview -->
                        <div class="mb-6 p-4 bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg">
                            <h4 class="text-sm font-medium text-orange-900 dark:text-orange-100 mb-2">📦 Report Contents</h4>
                            <ul class="text-sm text-orange-700 dark:text-orange-300 space-y-1">
                                <li>• Complete product catalog with specifications</li>
                                <li>• Product categories and organization</li>
                                <li>• Usage statistics and performance metrics</li>
                                <li>• Top 10 most used products</li>
                                <li>• Active vs inactive product counts</li>
                                <li>• PDF attachment with detailed information</li>
                            </ul>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end">
                            <x-secondary-button href="{{ route('reports.index') }}" class="mr-3">
                                {{ __('Cancel') }}
                            </x-secondary-button>
                            <x-primary-button type="submit">
                                {{ __('Send Products Report') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
