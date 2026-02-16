<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Email Reports') }}
            </h2>
            <x-back-button href="{{ route('reports.index') }}" />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Send Job Status Reports</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Send automated reports to clients, staff, and managers</p>
                    </div>

                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Send to Client -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Send to Client</h4>
                            <form action="{{ route('reports.send-to-client') }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label for="client_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Select Client</label>
                                    <select name="client_id" id="client_id" required
                                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Choose a client...</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}">{{ $client->first_name }} {{ $client->last_name }} ({{ $client->email }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="grid grid-cols-2 gap-2 mb-4">
                                    <div>
                                        <label for="date_from_client" class="block text-sm font-medium text-gray-700 dark:text-gray-300">From Date</label>
                                        <input type="date" name="date_from" id="date_from_client"
                                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label for="date_to_client" class="block text-sm font-medium text-gray-700 dark:text-gray-300">To Date</label>
                                        <input type="date" name="date_to" id="date_to_client"
                                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                </div>
                                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md">
                                    Send Report to Client
                                </button>
                            </form>
                        </div>

                        <!-- Send to Staff -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Send to Staff</h4>
                            <form action="{{ route('reports.send-to-staff') }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Send report to all technicians and storekeepers</p>
                                </div>
                                <div class="grid grid-cols-2 gap-2 mb-4">
                                    <div>
                                        <label for="date_from_staff" class="block text-sm font-medium text-gray-700 dark:text-gray-300">From Date</label>
                                        <input type="date" name="date_from" id="date_from_staff"
                                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label for="date_to_staff" class="block text-sm font-medium text-gray-700 dark:text-gray-300">To Date</label>
                                        <input type="date" name="date_to" id="date_to_staff"
                                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                </div>
                                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md">
                                    Send Report to Staff
                                </button>
                            </form>
                        </div>

                        <!-- Send to Managers -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Send to Managers</h4>
                            <form action="{{ route('reports.send-to-managers') }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Send detailed report to all administrators</p>
                                </div>
                                <div class="grid grid-cols-2 gap-2 mb-4">
                                    <div>
                                        <label for="date_from_managers" class="block text-sm font-medium text-gray-700 dark:text-gray-300">From Date</label>
                                        <input type="date" name="date_from" id="date_from_managers"
                                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label for="date_to_managers" class="block text-sm font-medium text-gray-700 dark:text-gray-300">To Date</label>
                                        <input type="date" name="date_to" id="date_to_managers"
                                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                </div>
                                <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-md">
                                    Send Report to Managers
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Scheduled Reports Info -->
                    <div class="mt-8 bg-blue-50 dark:bg-blue-900/20 p-6 rounded-lg">
                        <h4 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-2">Automated Reports</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h5 class="font-medium text-blue-800 dark:text-blue-200">Daily Reports</h5>
                                <p class="text-sm text-blue-700 dark:text-blue-300">Sent every morning at 9 AM to managers and staff with previous day's activity</p>
                            </div>
                            <div>
                                <h5 class="font-medium text-blue-800 dark:text-blue-200">Weekly Reports</h5>
                                <p class="text-sm text-blue-700 dark:text-blue-300">Sent every Monday at 9 AM to managers with previous week's summary</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <p class="text-xs text-blue-600 dark:text-blue-400">
                                Note: Automated reports are sent via scheduled jobs. Contact your administrator if you need to modify the schedule.
                            </p>
                        </div>
                    </div>

                    <!-- Report Types Info -->
                    <div class="mt-6 bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Report Features</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h5 class="font-medium text-gray-800 dark:text-gray-200">📧 Email Format</h5>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Professional HTML emails with status badges and detailed information</p>
                            </div>
                            <div>
                                <h5 class="font-medium text-gray-800 dark:text-gray-200">📄 PDF Attachment</h5>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Detailed PDF report included for printing and record keeping</p>
                            </div>
                            <div>
                                <h5 class="font-medium text-gray-800 dark:text-gray-200">📊 Excel Attachment</h5>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Excel spreadsheet included for data analysis and filtering</p>
                            </div>
                            <div>
                                <h5 class="font-medium text-gray-800 dark:text-gray-200">📊 Summary Statistics</h5>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Job counts, completion rates, and status breakdowns</p>
                            </div>
                            <div>
                                <h5 class="font-medium text-gray-800 dark:text-gray-200">👤 User Tracking</h5>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Shows who received jobs and who changed statuses</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>