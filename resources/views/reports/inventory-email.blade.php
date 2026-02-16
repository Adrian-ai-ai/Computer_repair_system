<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Send Inventory Report
            </h2>
            <x-back-button href="{{ route('reports.index') }}" />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Inventory Email Report</h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Send comprehensive inventory reports to staff members</p>
                    </div>

                    <form method="POST" action="{{ route('reports.inventory.send') }}">
                        @csrf

                        <!-- Recipients -->
                        <div class="mb-6">
                            <x-input-label for="recipients" :value="__('Recipients')" />
                            <div class="mt-2 space-y-2">
                                @foreach($staff as $user)
                                <label class="flex items-center">
                                    <input type="checkbox" name="recipients[]" value="{{ $user->id }}" 
                                           class="rounded border-gray-300 dark:border-gray-600 text-purple-600 shadow-sm focus:ring-purple-500 dark:focus:ring-purple-600 dark:focus:ring-offset-gray-800">
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
                                         value="Inventory Report - {{ now()->format('M d, Y') }}" required />
                            <x-input-error :messages="$errors->get('subject')" class="mt-2" />
                        </div>

                        <!-- Message -->
                        <div class="mb-6">
                            <x-input-label for="message" :value="__('Message (Optional)')" />
                            <textarea id="message" name="message" rows="4" 
                                      class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 dark:focus:ring-purple-600 dark:focus:ring-offset-gray-800"
                                      placeholder="Add a custom message to include with the inventory report..."></textarea>
                            <x-input-error :messages="$errors->get('message')" class="mt-2" />
                        </div>

                        <!-- Report Preview -->
                        <div class="mb-6 p-4 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg">
                            <h4 class="text-sm font-medium text-purple-900 dark:text-purple-100 mb-2">📊 Report Contents</h4>
                            <ul class="text-sm text-purple-700 dark:text-purple-300 space-y-1">
                                <li>• Current stock levels for all products</li>
                                <li>• Stock movements (in/out) summary</li>
                                <li>• Low stock alerts and critical items</li>
                                <li>• Total inventory value calculation</li>
                                <li>• Today's stock movements count</li>
                                <li>• PDF attachment with detailed tables</li>
                            </ul>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end">
                            <x-secondary-button href="{{ route('reports.index') }}" class="mr-3">
                                {{ __('Cancel') }}
                            </x-secondary-button>
                            <x-primary-button type="submit">
                                {{ __('Send Inventory Report') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
