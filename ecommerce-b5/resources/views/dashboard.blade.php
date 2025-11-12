<x-app-layout :title="$title ?? 'Dashboard'">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
                        @foreach($data_dashboard as $key => $value)
                        <div class="bg-white border-[2px] p-6 rounded-lg">
                            <div class="flex items-center gap-2 mb-4 {{ $key == 'total_stock' ? ($value['value'] > 50 ? 'text-[#10b981]' : 'text-[#ef4444]') : 'text-gray-500' }}">
                                <span class="material-symbols-rounded text-[20px]">{{ $value['icon'] }}</span>
                                <h3 class="text-lg font-semibold ">{{ ucfirst(
                                    str_replace('_', ' ', $key)
                                    ) }}</h3>
                            </div>
                            <p class="text-4xl font-bold" style="color: {{ $value['color'] }}">{{ $value['value'] }}</p>
                        </div>
                        @endforeach
                    </div>

                    <!-- Sales Chart Section -->
                    <div class="bg-white border-[2px] p-6 rounded-lg mt-8">
                        <div class="flex items-center gap-2 mb-6">
                            <span class="material-symbols-rounded text-[20px] text-blue-600">trending_up</span>
                            <h3 class="text-xl font-semibold text-gray-800">Penjualan 7 Hari Terakhir</h3>
                        </div>
                        <div class="relative" style="height: 400px;">
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        // Data dummy untuk penjualan 7 hari terakhir
        const ctx = document.getElementById('salesChart').getContext('2d');
        
        // Generate tanggal 7 hari terakhir
        const labels = [];
        const today = new Date();
        for (let i = 6; i >= 0; i--) {
            const date = new Date(today);
            date.setDate(today.getDate() - i);
            labels.push(date.toLocaleDateString('id-ID', { 
                weekday: 'short', 
                day: 'numeric', 
                month: 'short' 
            }));
        }
        
        // Data dummy penjualan (dalam jutaan rupiah)
        const salesData = [12.5, 8.3, 15.7, 22.1, 18.4, 25.6, 19.8];
        
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Penjualan (Juta Rp)',
                    data: salesData,
                    backgroundColor: 'rgba(59, 130, 246, 0.8)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                size: 12,
                                weight: '500'
                            },
                            color: '#374151'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.9)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#6B7280',
                        borderWidth: 1,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return 'Penjualan: Rp ' + context.parsed.y.toFixed(1) + ' Juta';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(156, 163, 175, 0.2)',
                            drawBorder: false
                        },
                        ticks: {
                            font: {
                                size: 11
                            },
                            color: '#6B7280',
                            callback: function(value) {
                                return 'Rp ' + value + 'M';
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 11,
                                weight: '500'
                            },
                            color: '#374151'
                        }
                    }
                },
                animation: {
                    duration: 2000,
                    easing: 'easeInOutQuart'
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
    </script>
</x-app-layout>
