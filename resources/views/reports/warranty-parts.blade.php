<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Warranty Parts Usage Report') }}
            </h2>
            <x-back-button href="{{ route('reports.index') }}" />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Warranty Parts Usage</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Parts used under warranty claims, ordered by total usage</p>
                    </div>

                    @if($warrantyParts->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Product Name</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total Used Under Warranty</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Usage Rank</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($warrantyParts as $index => $part)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $part->name }}</div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $part->total_used }}</div>
                                                    <div class="ml-2 flex items-center">
                                                        @if($part->total_used > 10)
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                                High Usage
                                                            </span>
                                                        @elseif($part->total_used > 5)
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                                Moderate
                                                            </span>
                                                        @else
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                                Low Usage
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($index === 0) bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                    @elseif($index === 1) bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                                                    @elseif($index === 2) bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                                                    @else bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 @endif">
                                                    @if($index === 0)
                                                        🥇 #{{ $index + 1 }}
                                                    @elseif($index === 1)
                                                        🥈 #{{ $index + 1 }}
                                                    @elseif($index === 2)
                                                        🥉 #{{ $index + 1 }}
                                                    @else
                                                        #{{ $index + 1 }}
                                                    @endif
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Reports Pagination -->
                        @if($warrantyParts->hasPages())
                            <div class="mt-6">
                                {{ $warrantyParts->links() }}
                            </div>
                        @endif

                        <!-- Summary Statistics -->
                        <div class="mt-8 bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                            <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Report Summary</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $warrantyParts->total() }}</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">Total Products</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $warrantyParts->sum('total_used') }}</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">Total Parts Used</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $warrantyParts->avg('total_used') ? number_format($warrantyParts->avg('total_used'), 1) : 0 }}</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">Average Usage</div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No warranty parts usage data</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Warranty parts usage will appear here once warranty repairs are completed.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>