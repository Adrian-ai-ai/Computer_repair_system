<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
    <canvas id="stockChart"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const products = @json($products);

new Chart(document.getElementById('stockChart'), {
    type: 'bar',
    data: {
        labels: products.map(p => p.name),
        datasets: [{
            label: 'Current Stock',
            data: products.map(p => (p.stock_in ?? 0) - (p.stock_out ?? 0))
        }]
    }
});
</script>

</x-app-layout>
