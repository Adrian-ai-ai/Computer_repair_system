<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Activity Summary Report
            </h2>
            <x-back-button href="{{ route('reports.index') }}" />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">
            <!-- Recent Jobs -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Recent Jobs (Last 7 Days)</h3>
                    @if($recentJobs->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Job Number</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Client</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Device</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Received</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($recentJobs as $job)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $job->job_number }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                {{ $job->client ? $job->client->first_name . ' ' . $job->client->last_name : 'No Client' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                {{ $job->device_type }} - {{ $job->brand }} {{ $job->model }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($job->status == 'Received') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                    @elseif($job->status == 'Diagnosing') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                    @elseif($job->status == 'Waiting for parts') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                                                    @elseif($job->status == 'Repairing') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                                                    @elseif($job->status == 'Ready for pickup') bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200
                                                    @elseif($job->status == 'Delivered') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                    @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                                    {{ $job->status }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                {{ $job->received_at->format('M d, Y') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No recent jobs</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">No jobs have been received in the last 7 days.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Monthly Trends -->
            @if($monthlyTrends->count() > 0)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Monthly Job Trends (Last 6 Months)</h3>
                    <div class="space-y-4">
                        @foreach($monthlyTrends as $trend)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-4 h-4 bg-indigo-500 rounded mr-3"></div>
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ \Carbon\Carbon::parse($trend->month)->format('F Y') }}
                                    </span>
                                </div>
                                <div class="flex items-center">
                                    <span class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $trend->total }}</span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400 ml-2">jobs</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
