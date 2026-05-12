<x-app-layout>
    @if(Auth::user()->roles->count() === 0)
    <div class="flex flex-col items-center justify-center min-h-[60vh] text-center px-4">
        <div class="bg-[#EADDFF] p-6 rounded-[32px] mb-6">
            <svg class="w-16 h-16 text-[#21005D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
        </div>
        <h2 class="text-3xl font-bold text-[#1C1B1F] mb-4">Selamat Datang, {{ Auth::user()->name }}!</h2>
        <p class="text-[#49454F] max-w-md text-lg leading-relaxed">
            Akun Anda telah berhasil terdaftar. Silahkan hubungi administrator sistem untuk mendapatkan hak akses sesuai dengan peran Anda.
        </p>
        <div class="mt-8 flex gap-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="px-8 py-3 rounded-full border border-[#CAC4D0] text-[#6750A4] font-semibold hover:bg-[#F3EDF7] transition-all">
                    Keluar
                </button>
            </form>
        </div>
    </div>
    @else
    <div x-data="dashboard({{ json_encode($revenueData) }}, {{ json_encode($itemQuantityData) }})" x-init="initCharts()">
        <!-- Top Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Transaksi -->
            <div class="bg-[#EADDFF] rounded-[28px] p-8 shadow-sm">
                <div class="flex items-center gap-4 mb-4 text-[#21005D]">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/></svg>
                    <span class="font-medium uppercase tracking-wider text-xs">Total Transaksi</span>
                </div>
                <div class="text-4xl font-bold text-[#21005D]">{{ number_format($totalTransactions) }}</div>
            </div>

            <!-- Total Pendapatan -->
            <div class="bg-[#F3EDF7] rounded-[28px] p-8 shadow-sm border border-[#CAC4D0]">
                <div class="flex items-center gap-4 mb-4 text-[#49454F]">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/></svg>
                    <span class="font-medium uppercase tracking-wider text-xs">Total Pendapatan</span>
                </div>
                <div class="text-4xl font-bold text-[#1C1B1F]">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            </div>

            <!-- Total Barang Terjual -->
            <div class="bg-[#FEF7FF] rounded-[28px] p-8 shadow-sm border border-[#CAC4D0]">
                <div class="flex items-center gap-4 mb-4 text-[#6750A4]">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M20 7h-4V5c0-1.1-.9-2-2-2h-4c-1.1 0-2 .9-2 2v2H4c-1.1 0-2 .9-2 2v11c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V9c0-1.1-.9-2-2-2zM10 5h4v2h-4V5zm10 15H4V9h5V7h6v2h5v11z"/></svg>
                    <span class="font-medium uppercase tracking-wider text-xs">Barang Terjual</span>
                </div>
                <div class="text-4xl font-bold text-[#6750A4]">{{ number_format($totalQuantitySold) }}</div>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Grafik Pendapatan Bulanan -->
            <div class="bg-white rounded-[28px] p-8 border border-[#CAC4D0] shadow-sm">
                <h3 class="text-lg font-semibold text-[#1C1B1F] mb-6">Pendapatan Bulanan (Rupiah)</h3>
                <div class="h-80">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Grafik Jumlah Terjual per Barang -->
            <div class="bg-white rounded-[28px] p-8 border border-[#CAC4D0] shadow-sm flex flex-col h-full">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-[#1C1B1F]">Jumlah Terjual per Barang</h3>
                </div>

                <div class="h-80 overflow-y-auto pr-2 custom-scrollbar">
                    <div x-ref="itemChartContainer" class="w-full">
                        <canvas id="itemChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #F3EDF7; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #6750A4; border-radius: 10px; }
    </style>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function dashboard(revenueData, itemData) {
            return {
                revenueData: revenueData,
                itemData: itemData,
                revenueChart: null,
                itemChart: null,

                initCharts() {
                    this.initRevenueChart();
                    this.initItemChart();
                },

                initRevenueChart() {
                    const ctx = document.getElementById('revenueChart').getContext('2d');
                    const allLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                    const currentLabels = allLabels.slice(0, this.revenueData.length);
                    
                    this.revenueChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: currentLabels,
                            datasets: [{
                                label: 'Pendapatan',
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
                    const chartData = this.getItemChartData();
                    const ctx = document.getElementById('itemChart').getContext('2d');
                    
                    // Custom Plugin to draw labels on the right
                    const dataLabelsPlugin = {
                        id: 'dataLabels',
                        afterDatasetsDraw(chart, args, options) {
                            const { ctx, data, chartArea: { top, bottom, left, right, width, height }, scales: { x, y } } = chart;
                            ctx.save();
                            ctx.font = 'bold 12px Inter';
                            ctx.fillStyle = '#49454F';
                            ctx.textAlign = 'left';
                            ctx.textBaseline = 'middle';

                            data.datasets[0].data.forEach((value, index) => {
                                const bar = chart.getDatasetMeta(0).data[index];
                                if (value > 0) {
                                    ctx.fillText(value, bar.x + 8, bar.y);
                                }
                            });
                        }
                    };

                    this.itemChart = new Chart(ctx, {
                        type: 'bar',
                        data: chartData,
                        plugins: [dataLabelsPlugin],
                        options: {
                            indexAxis: 'y',
                            maintainAspectRatio: false,
                            layout: { padding: { right: 40 } },
                            plugins: { 
                                legend: { display: false },
                                tooltip: {
                                    callbacks: { label: (item) => `Jumlah: ${item.raw}` }
                                }
                            },
                            scales: {
                                x: {
                                    display: false,
                                    beginAtZero: true,
                                    grid: { display: false }
                                },
                                y: {
                                    grid: { display: false },
                                    ticks: {
                                        autoSkip: false,
                                        font: { size: 11, weight: '500' }
                                    }
                                }
                            }
                        }
                    });
                    this.updateItemChartHeight();
                },

                getItemChartData() {
                    let visibleItems = this.itemData
                        .map(item => ({
                            ...item,
                            totalQty: item.data.reduce((a, b) => a + b, 0)
                        }))
                        .sort((a, b) => b.totalQty - a.totalQty);

                    return {
                        labels: visibleItems.map(item => item.name),
                        datasets: [{
                            label: 'Total Jumlah Terjual',
                            data: visibleItems.map(item => item.totalQty),
                            backgroundColor: visibleItems.map(item => item.color),
                            borderColor: visibleItems.map(item => item.color),
                            borderWidth: 1,
                            borderRadius: 6
                        }]
                    };
                },

                updateItemChartHeight() {
                    const visibleCount = this.itemData.length;
                    const container = this.$refs.itemChartContainer;
                    const rowHeight = 44; 
                    const calculatedHeight = Math.max(320, visibleCount * rowHeight);
                    container.style.height = calculatedHeight + 'px';
                }
            }
        }
    </script>
    @endpush
    @endif
</x-app-layout>
