<x-app-layout>
    <div x-data="dashboard({{ json_encode($revenueData) }}, {{ json_encode($itemQuantityData) }})" x-init="initCharts()">
        <!-- Top Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Transactions -->
            <div class="bg-[#EADDFF] rounded-[28px] p-8 shadow-sm">
                <div class="flex items-center gap-4 mb-4 text-[#21005D]">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/></svg>
                    <span class="font-medium uppercase tracking-wider text-xs">Total Transactions</span>
                </div>
                <div class="text-4xl font-bold text-[#21005D]">{{ number_format($totalTransactions) }}</div>
            </div>

            <!-- Total Revenue -->
            <div class="bg-[#F3EDF7] rounded-[28px] p-8 shadow-sm border border-[#CAC4D0]">
                <div class="flex items-center gap-4 mb-4 text-[#49454F]">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/></svg>
                    <span class="font-medium uppercase tracking-wider text-xs">Total Sales Revenue</span>
                </div>
                <div class="text-4xl font-bold text-[#1C1B1F]">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            </div>

            <!-- Total Quantity (Clickable to Filter) -->
            <button @click="isItemModalOpen = true" class="bg-[#FEF7FF] rounded-[28px] p-8 shadow-sm border border-[#CAC4D0] hover:bg-[#F3EDF7] transition-all text-left group">
                <div class="flex items-center gap-4 mb-4 text-[#6750A4]">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M20 7h-4V5c0-1.1-.9-2-2-2h-4c-1.1 0-2 .9-2 2v2H4c-1.1 0-2 .9-2 2v11c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V9c0-1.1-.9-2-2-2zM10 5h4v2h-4V5zm10 15H4V9h5V7h6v2h5v11z"/></svg>
                    <span class="font-medium uppercase tracking-wider text-xs">Items Sold</span>
                </div>
                <div class="text-4xl font-bold text-[#6750A4]">{{ number_format($totalQuantitySold) }}</div>
                <div class="mt-2 text-xs text-[#6750A4] font-medium flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                    Filter items on chart
                </div>
            </button>
        </div>

        <!-- Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Monthly Revenue Chart -->
            <div class="bg-white rounded-[28px] p-8 border border-[#CAC4D0] shadow-sm">
                <h3 class="text-lg font-semibold text-[#1C1B1F] mb-6">Monthly Revenue (Rupiah)</h3>
                <div class="h-80">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Item Quantity Chart -->
            <div class="bg-white rounded-[28px] p-8 border border-[#CAC4D0] shadow-sm flex flex-col">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-[#1C1B1F]">Quantity Sold per Item</h3>
                    <button @click="isItemModalOpen = true" class="text-sm font-medium text-[#6750A4] hover:underline">Filter Items</button>
                </div>

                <div class="h-80 flex-1">
                    <canvas id="itemChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Item Filter Modal -->
        <x-m3.modal show="isItemModalOpen" close="isItemModalOpen = false" title="'Filter Items on Chart'" maxWidth="md">
            <div class="space-y-4">
                <p class="text-sm text-[#49454F] mb-4">Select which items to display in the quantity sold chart.</p>
                <div class="grid grid-cols-1 gap-2 max-h-[60vh] overflow-y-auto pr-2">
                    <template x-for="item in itemData" :key="item.id">
                        <label class="flex items-center justify-between p-4 rounded-2xl border border-[#CAC4D0] cursor-pointer transition-all hover:bg-[#F3EDF7]"
                               :style="selectedItems.includes(item.id) ? `background-color: ${item.color}10; border-color: ${item.color}` : ''">
                            <div class="flex items-center gap-3">
                                <div class="w-4 h-4 rounded-full" :style="`background-color: ${item.color}`"></div>
                                <span class="font-medium text-[#1C1B1F]" x-text="item.name"></span>
                            </div>
                            <input type="checkbox" :value="item.id" x-model="selectedItems" @change="updateItemChart()" 
                                   class="w-5 h-5 rounded border-[#79747E] text-[#6750A4] focus:ring-[#6750A4]">
                        </label>
                    </template>
                </div>
                <div class="pt-4 flex justify-end">
                    <button @click="isItemModalOpen = false" class="bg-[#6750A4] text-white font-semibold px-8 py-2.5 rounded-full hover:bg-[#4F378B] shadow-md transition-all">
                        Done
                    </button>
                </div>
            </div>
        </x-m3.modal>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function dashboard(revenueData, itemData) {
            return {
                revenueData: revenueData,
                itemData: itemData,
                selectedItems: itemData.map(i => i.id),
                revenueChart: null,
                itemChart: null,

                initCharts() {
                    this.initRevenueChart();
                    this.initItemChart();
                },

                initRevenueChart() {
                    const ctx = document.getElementById('revenueChart').getContext('2d');
                    this.revenueChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                            datasets: [{
                                label: 'Revenue',
                                data: this.revenueData,
                                borderColor: '#6750A4',
                                backgroundColor: '#6750A420',
                                tension: 0.4,
                                fill: true,
                                pointRadius: 4,
                                pointBackgroundColor: '#6750A4'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                        }
                                    }
                                }
                            }
                        }
                    });
                },

                initItemChart() {
                    const ctx = document.getElementById('itemChart').getContext('2d');
                    this.itemChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                            datasets: this.getVisibleDatasets()
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: { stepSize: 1 }
                                }
                            }
                        }
                    });
                },

                getVisibleDatasets() {
                    return this.itemData
                        .filter(item => this.selectedItems.includes(item.id))
                        .map(item => ({
                            label: item.name,
                            data: item.data,
                            borderColor: item.color,
                            backgroundColor: item.color + '20',
                            tension: 0.3,
                            pointRadius: 3
                        }));
                },

                updateItemChart() {
                    this.itemChart.data.datasets = this.getVisibleDatasets();
                    this.itemChart.update();
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
