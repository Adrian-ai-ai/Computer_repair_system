<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Reports Dashboard') }}
            </h2>
            <x-back-button href="{{ route('dashboard') }}" />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Reports & Analytics</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Access various reports and send email notifications</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @if(in_array(auth()->user()->role, ['admin', 'storekeeper']))
                        <!-- Inventory Report -->
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 p-6 rounded-lg border border-purple-200 dark:border-purple-800">
                            <div class="flex items-center mb-4">
                                <div class="bg-purple-500 rounded-full p-3 mr-5">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Inventory Report</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Stock levels & movements</p>
                                </div>
                            </div>
                            <p class="text-sm text-gray-700 dark:text-gray-300 mb-4">
                                Send comprehensive inventory reports with current stock levels, movements, and low stock alerts.
                            </p>
                            <a href="{{ route('reports.inventory.email') }}"
                               class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out">
                                Send Inventory Report
                                <svg class="ml-2 -mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>

                        <!-- Products Report -->
                        <div class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 p-6 rounded-lg border border-orange-200 dark:border-orange-800">
                            <div class="flex items-center mb-4">
                                <div class="bg-orange-500 rounded-full p-3 mr-5">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Products Report</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Product catalog & usage</p>
                                </div>
                            </div>
                            <p class="text-sm text-gray-700 dark:text-gray-300 mb-4">
                                Send detailed product reports including catalog information, usage statistics, and performance metrics.
                            </p>
                            <a href="{{ route('reports.products.email') }}"
                               class="inline-flex items-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out">
                                Send Products Report
                                <svg class="ml-2 -mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                        @endif

                        @if(auth()->user()->role === 'admin')
                        <!-- Email Reports -->
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 p-6 rounded-lg border border-blue-200 dark:border-blue-800">
                            <div class="flex items-center mb-4">
                                <div class="bg-blue-500 rounded-full p-3 mr-5">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Job Status Reports</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Send job reports</p>
                                </div>
                            </div>
                            <p class="text-sm text-gray-700 dark:text-gray-300 mb-4">
                                Send automated job status reports to clients, staff, and managers with PDF attachments.
                            </p>
                            <a href="{{ route('reports.email') }}"
                               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out">
                                Send Job Reports
                                <svg class="ml-2 -mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                        @endif

                        @if(in_array(auth()->user()->role, ['admin', 'storekeeper']))
                        <!-- Warranty Parts Report -->
                        <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 p-6 rounded-lg border border-green-200 dark:border-green-800">
                            <div class="flex items-center mb-4">
                                <div class="bg-green-500 rounded-full p-3 mr-5">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Warranty Parts</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Usage analytics</p>
                                </div>
                            </div>
                            <p class="text-sm text-gray-700 dark:text-gray-300 mb-4">
                                View warranty parts usage statistics and identify high-usage components.
                            </p>
                            <a href="{{ route('reports.warranty-parts') }}"
                               class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out">
                                View Report
                                <svg class="ml-2 -mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                        @endif

                        @if(auth()->user()->role === 'technician')
                        <!-- Technician Reports -->
                        <div class="bg-gradient-to-br from-teal-50 to-teal-100 dark:from-teal-900/20 dark:to-teal-800/20 p-6 rounded-lg border border-teal-200 dark:border-teal-800">
                            <div class="flex items-center mb-4">
                                <div class="bg-teal-500 rounded-full p-3 mr-5">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">My Jobs Report</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Personal performance</p>
                                </div>
                            </div>
                            <p class="text-sm text-gray-700 dark:text-gray-300 mb-4">
                                View your assigned jobs, completion rates, and performance metrics.
                            </p>
                            <a href="{{ route('reports.technician.jobs') }}"
                               class="inline-flex items-center px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out">
                                View My Jobs
                                <svg class="ml-2 -mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                        @endif

                        <!-- Activity Summary Report -->
                        <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 dark:from-indigo-900/20 dark:to-indigo-800/20 p-6 rounded-lg border border-indigo-200 dark:border-indigo-800">
                            <div class="flex items-center mb-4">
                                <div class="bg-indigo-500 rounded-full p-3 mr-5">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Activity Summary</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">System activity overview</p>
                                </div>
                            </div>
                            <p class="text-sm text-gray-700 dark:text-gray-300 mb-4">
                                View comprehensive summary of all system activity including jobs, inventory movements, and performance metrics.
                            </p>
                            <a href="{{ route('reports.activity') }}"
                               class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out">
                                View Report
                                <svg class="ml-2 -mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>

                        <!-- Future Reports -->
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900/20 dark:to-gray-800/20 p-6 rounded-lg border border-gray-200 dark:border-gray-800 opacity-60">
                            <div class="flex items-center mb-4">
                                <div class="bg-gray-400 rounded-full p-3 mr-5">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">More Reports</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Coming soon</p>
                                </div>
                            </div>
                            <p class="text-sm text-gray-700 dark:text-gray-300 mb-4">
                                Additional reports like inventory analytics, technician performance, and financial summaries.
                            </p>
                            <button disabled
                               class="inline-flex items-center px-4 py-2 bg-gray-400 text-white text-sm font-medium rounded-md cursor-not-allowed">
                                Coming Soon
                            </button>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="mt-8 bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Quick Statistics</h4>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-blue-600">{{ \App\Models\Job::count() }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">Total Jobs</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-600">{{ \App\Models\Job::where('status', 'Delivered')->count() }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">Completed Jobs</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-yellow-600">{{ \App\Models\Job::where('status', 'Waiting for parts')->count() }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">Waiting for Parts</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-purple-600">{{ \App\Models\Client::count() }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">Total Clients</div>
                            </div>
                        </div>
                    </div>

                    <!-- Automated Reports Info -->
                    <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 p-6 rounded-lg">
                        <h4 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-2">Automated Email Reports</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h5 class="font-medium text-blue-800 dark:text-blue-200">Daily Reports</h5>
                                <p class="text-sm text-blue-700 dark:text-blue-300">Sent every weekday at 9 AM to managers and staff with previous day's activity</p>
                            </div>
                            <div>
                                <h5 class="font-medium text-blue-800 dark:text-blue-200">Weekly Reports</h5>
                                <p class="text-sm text-blue-700 dark:text-blue-300">Sent every Monday at 9 AM to managers with previous week's summary</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <p class="text-xs text-blue-600 dark:text-blue-400">
                                💡 <strong>Tip:</strong> Use the Email Reports section above to send on-demand reports to specific recipients.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>